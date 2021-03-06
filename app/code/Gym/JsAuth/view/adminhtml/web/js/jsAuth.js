define([
    'faceApi',
    'jquery',
    'vue',
    'domReady!'
], function jsAuth(
    faceApi,
    jQuery,
    Vue
) {

    'use strict';


    return function (config) {
        if (faceApi.draw.DrawBox && faceApi.draw.DrawBox.prototype && faceApi.draw.DrawBox.prototype.draw) {
            faceApi.draw.DrawBox.prototype.draw = function draw(canvasArg) {
                const ctx = faceApi.getContext2dOrThrow(canvasArg)
                const { boxColor, lineWidth } = this.options
                const { x, y, width, height } = this.box
                ctx.fillStyle = "rgba(1, 255, 27, 0.2)"
                ctx.fillRect(x, y, width, height)
                const { label } = this.options
                if (label) {
                    new faceApi.draw.DrawTextField([label], { x: x - (lineWidth / 2), y }, this.options.drawLabelOptions).draw(canvasArg)
                }
            }
        }


        const vueApp = new Vue({
            el: '#appvue',
            mounted: function mounted() {
                const self = this;
                this.video = document.getElementById('main-video');
                this.isProcessing = true;
                if (this.video) {
                    Promise.all([
                        faceApi.nets.tinyFaceDetector.loadFromUri(self.config.assetBaseUrl + '/models'),
                        faceApi.nets.ssdMobilenetv1.loadFromUri(self.config.assetBaseUrl + '/models'),
                        faceApi.nets.faceRecognitionNet.loadFromUri(self.config.assetBaseUrl + '/models')
                    ]).then(function () {
                        self.initVideo();
                        self.isProcessing = false;
                    });
                }
            },
            data: {
                config: config,
                video: null,
                faceDetected: null,
                faceDetectedImg: null,
                identifiedPerson: null,
                info: { msg: 'Bienvenido', type: 'primary' },
                ci: null,
                isProcessing: false,
                timeToMakeRegister: parseInt(config.timeToMakeRegister, 10) || 2,
                timeDetecting: 0,
                inputSize: 224,
                errorDetecting: 0.7,
                timeWithIdentifiedPerson: null
            },
            computed: {
                disableRegisterWithFace: function disableRegisterWithFace() {
                    return this.isProcessing || !this.faceDetected;
                },
                showUserData: function showUserData() {
                    return this.identifiedPerson && this.identifiedPerson.id;
                }
            },
            methods: {
                initVideo: function initVideo() {
                    var self = this;
                    if (navigator.getUserMedia) {
                        navigator.getUserMedia(
                            { video: {} },
                            function (stream) {
                                self.video.srcObject = stream;
                                self.video.addEventListener('playing', () => {
                                    self.initFaceDetection()
                                });
                            },
                            err => console.error(err)
                        );
                    } else if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                        navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
                            self.video.srcObject = stream;
                            self.video.addEventListener('playing', () => {
                                self.initFaceDetection()
                            });
                        })
                    } else {
                        console.error('Camara no soportada por el navegador')
                    }
                },
                initFaceDetection: async function initFaceDetection() {
                    
                    var detection = null;
                    var resizedDetections;
                    var self = this;
                    const detectionsOptions = new faceApi.TinyFaceDetectorOptions({ inputSize: parseInt(this.inputSize, 10) })

                    this.faceDetected = null;
                    this.identifiedPerson = null;
                    
                    self.canvas = faceApi.createCanvasFromMedia(self.video);
                    self.video.parentElement.append(self.canvas)

                    setInterval(async () => {
                        var displaySize = {
                            width: this.video.clientWidth,
                            height: this.video.clientHeight
                        };
                        faceApi.matchDimensions(self.canvas, displaySize);
                        detection = await faceApi.detectSingleFace(self.video, detectionsOptions);
                        self.canvas.getContext('2d').clearRect(0, 0, self.canvas.width, self.canvas.height);
                        
                        if (detection && detection.score > (parseFloat(this.errorDetecting) || 0.7) && !self.isProcessing) {
                            self.faceDetected = true;
                            resizedDetections = faceApi.resizeResults([detection], displaySize);
                            faceApi.draw.drawDetections(self.canvas, resizedDetections);
                            if (self.timeDetecting < self.timeToMakeRegister) {
                                self.timeDetecting += 0.3;
                            } else {
                                self.timeDetecting = self.timeToMakeRegister;
                                self.registerWithFace();
                            }
                        } else {
                            self.faceDetected = false;
                            self.timeDetecting = 0
                        }
                        // hide handler user data
                        if (self.showUserData && self.timeWithIdentifiedPerson !== null) {
                            self.timeWithIdentifiedPerson += 0.3;
                        } else if(self.showUserData && self.timeWithIdentifiedPerson === null) {
                            self.timeWithIdentifiedPerson = 0;
                        } else {
                            self.timeWithIdentifiedPerson = null;
                        }
                        if (self.timeWithIdentifiedPerson !== null && self.timeWithIdentifiedPerson > 60) {
                            self.timeWithIdentifiedPerson = null;
                            self.identifiedPerson = null;
                            self.resetValues();
                        }
                    }, 300);
                },
                registerWithFace: function registerWithFace() {
                    if (!this.isProcessing) {
                        this.identifiedPerson = null;
                        this.isProcessing = true;
                        this.faceDetectedImg = this.getImageFromVideo();
                        this.checkFaceWithLuxand();
                    }
                },
                checkFaceWithLuxand: function checkFaceWithLuxand() {
                    const self = this;
                    var settings = {
                        "async": true,
                        "crossDomain": true,
                        "dataType": "json",
                        "url": this.config.luxandApiUrl,
                        "method": "POST",
                        "headers": {
                            "token": this.config.luxandToken
                        },
                        "data": {"photo": this.faceDetectedImg }
                    }
                    self.setInfo(true, 'Procesando...', 'warning');
                    self.identifiedPerson = null;
                    return jQuery.ajax(settings)
                    .done(function(response) {
                        self.identifiedPerson = self.handleResponseApi(response);
                        if (self.identifiedPerson) {
                            self.accessRegister();
                        } else {
                            self.setInfo(false, 'No se pudo identificar, por favor vuelva a intentarlo.', 'error');
                            self.isProcessing = false;
                            self.timeDetecting = 0;
                        }
                        
                    })
                    .fail(function () {
                        self.setInfo(false, 'No se pudo identificar.', 'error');
                        self.isProcessing = false;
                        self.timeDetecting = 0;
                    })
                },
                accessRegister: function accessRegister() {
                    const settings = {
                        "async": true,
                        "dataType": "json",
                        "url": this.config.registerUrl,
                        "method": "POST",
                        "data": { 
                            "customer_id": this.identifiedPerson.id,
                            "image": this.faceDetectedImg
                        }
                    }
                    const self = this;
                    return jQuery.ajax(settings)
                        .done(function (response) {
                            self.setInfo(false, 'El registro se completo satisfactoriamente.', 'success');
                            if (response.success) {
                                self.setInfo(false, 'El registro se completo satisfactoriamente.', 'success');
                                self.identifiedPerson = Object.assign(self.identifiedPerson, {
                                    to: response.plan && self.humanizeDate(response.plan.to)
                                })
                            } else {
                                self.setInfo(false, response.msg || 'El registro no se pudo completar.', 'error');
                            }
                            self.isProcessing = false;
                            self.timeDetecting = 0;
                        })
                        .fail(function () {
                            self.setInfo(false, 'El registro no se pudo completar.', 'error');
                            self.isProcessing = false;
                            self.timeDetecting = 0;
                        })
                },
                setInfo: function setInfo (showSpinner, msg, style) {
                    this.info = Object.assign({}, {
                        msg: msg, type: style, showSpinner: showSpinner
                    })
                },
                isValidCi: function isValidCi() {
                    if (!this.ci) {
                        return false;
                    }
                    if (isNaN(this.ci)) {
                        return false;
                    }
                    try {
                        return !!(parseInt(this.ci, 10) && parseInt(this.ci, 10) > 100);
                    } catch (e) {
                        console.log("invalid ci");
                    }
                    return false;
                },
                registerWithCi: function registerWithCi () {
                    this.resetValues();
                    const settings = {
                        "async": true,
                        "dataType": "json",
                        "url": this.config.registerUrl,
                        "method": "POST",
                        "data": { "ci": this.ci }
                    }
                    const self = this;
                    self.isProcessing = true;
                    if (!self.isValidCi()) {
                        self.setInfo(false, 'Ingrerse una CI correcta.', 'error');
                        return null;
                    } else {
                        self.setInfo(true, 'Procesando...', 'warning');
                        return jQuery.ajax(settings)
                            .done(function (response) {
                                if (response && response.success) {
                                    self.setInfo(false, 'El registro se completo satisfactoriamente.', 'success');
                                    self.identifiedPerson = Object.assign(self.identifiedPerson, {
                                        to: response.plan && self.humanizeDate(response.plan.to)
                                    })
                                } else {
                                    self.setInfo(false, response.msg || 'El registro no se pudo completar.', 'error');
                                }
                                if (response.customer && response.customer.success) {
                                    self.identifiedPerson = self.formatUserResponse(false, response.customer);
                                }
                                self.isProcessing = false;
                            })
                            .fail(function () {
                                self.setInfo(false, 'El registro no se pudo completar.', 'error');
                                self.isProcessing = false;
                            })
                    }

                },
                resetValues: function resetValues() {
                    this.faceDetected = null;
                    this.identifiedPerson = null;
                    this.timeWithIdentifiedPerson = null;
                    this.setInfo(false, 'Bienvenido', 'primary');
                    return null;
                },
                getImageFromVideo: function getImageFromVideo() {
                    const canvas = document.createElement('canvas');
                    canvas.width = this.video.videoWidth;
                    canvas.height = this.video.videoHeight;
                    canvas.getContext('2d')
                        .drawImage(this.video, 0, 0, canvas.width, canvas.height);
                    return canvas.toDataURL().replace('data:image/png;base64,', '');
                },
                formatUserResponse: function formatUserResponse(isFromLuxand, userData) {
                    if (isFromLuxand) {
                        return {
                            image: 'data:image/png;base64,' + userData.image64,
                            name: userData.name,
                            email: userData.email,
                            ci: userData.ci,
                            id: userData.id
                        }
                    } else {
                        return {
                            image: window.location.origin + '/pub/media' + userData.image,
                            name: userData.name,
                            email: userData.email,
                            ci: userData.ci,
                            id: userData.id
                        }
                    }
                },
                handleResponseApi: function handleResponseApi(response) {
                    var personArray;
                    var person;
                    try {
                        if (Array.isArray(response)) {
                            personArray = response.sort((a,b) => a.probability - b.probability).reverse();
                            if (personArray && personArray.length > 0 && personArray[0].probability > 0.8) {
                                person = personArray && personArray[0] && JSON.parse(personArray[0].name);
                                person.image64 = this.faceDetectedImg;
                                return this.formatUserResponse(true, person);
                            }
                        }
                    } catch (e) {
                        console.error(response);
                        console.error(e);
                    }
                    return false;
                },
                toggleConfigForm: function toggleConfigForm() {
                    jQuery('#form-configuracion').slideToggle()
                },
                humanizeDate: function formatDate (dateStr) {
                    var currentDate = new Date();
                    var dateArr = dateStr.split('-');
                    var sameYear = currentDate.getFullYear().toString() === dateArr[0];
                    var sameMonth = currentDate.getMonth() + 1 === parseInt(dateArr[1], 10);
                    var diffDay;

                    if (sameYear && sameMonth ) {
                        diffDay = Math.abs(currentDate.getDate() - parseInt(dateArr[2], 10));
                        if (diffDay <= 4) {
                            if (diffDay === 0) {
                                return 'Hoy es el último día del plan.';
                            }
                            if (diffDay === 1) {
                                return 'El plan vence mañana.';
                            }
                            return 'El plan vence en '  + diffDay + ' días.';
                        }
                    }
                    return 'Plan válido hasta '  + dateArr[2] + '/' + dateArr[1] + '/' + dateArr[0] + '.';
                }
            }
        });
    };

})
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'Magento_Ui/js/form/element/abstract',
    'faceApi',
    'jquery',
    'underscore',
    'domReady!'
], function (Abstract,faceApi,jQuery,_) {
    'use strict';

    return Abstract.extend({
        defaults: {
            elementTmpl: 'OY_Customer/input',
            active_video: false
        },

        initObservable: function () {
            this._super();
            this.observe('value');
            return this;
        },

        /**
         * Invokes initialize method of parent class,
         * contains initialization logic
         */
        initialize: function () {

            this._super();

            jQuery(document).on('click', '#made_photo', _.bind(this.takePhoto, this));
            jQuery(document).on('click', '#reload-video', _.bind(this.reloadVideo, this));
            jQuery(document).on('click', '#enable-camera', _.bind(this.initApp, this));
            this.activeDetection = false;

            jQuery(document).on('click',function (){
                if(jQuery('div[data-index="photo"]').hasClass('_error')){
                    jQuery('.cl_photo-error').show();
                }else{
                    jQuery('.cl_photo-error').hide();
                }
            })

            var self = this;
            jQuery( document ).on('DOMNodeInserted',function() {

                _.delay(function () {
                    if(jQuery('div[data-index="photo"]').length){
                        jQuery('div[data-index="photo"]').hide();
                        self.showImg();
                    }
                }, 1000);

            });

            return this;
        },

        showImg: function (){

            if(!this.active_video){

                var imgUrl = location.protocol+'//'+location.hostname+'/pub/media'+jQuery('input[name="customer[photo]"]').val();
                jQuery('#customer-image').attr("src",imgUrl);
                jQuery('#customer-image').show();
                jQuery('#customer-image').removeClass('hidden');
                jQuery('#customer-video').hide();

            }

        },

        hideImg: function (){
            jQuery('#customer-image').hide();
            jQuery('#customer-image').addClass('hidden');
            jQuery('#customer-video').show();
        },

        reloadVideo: function reloadVideo() {
            jQuery(this.image).addClass('hidden');
            jQuery(this.video).removeClass('hidden');
            jQuery(this.video).show();
            this.activeDetection = true;
        },

        takePhoto: function takePhoto() {
            var imgBase64 = this.getImageFromVideo();
            this.image = document.getElementById('customer-image');
            jQuery(this.image).attr('width', this.video.clientWidth);
            jQuery(this.image).attr('height', this.video.clientHeight);
            jQuery(this.image).attr('src', 'data:image/png;base64, ' + imgBase64);
            jQuery(this.image).show();
            jQuery(this.canvas).remove();
            jQuery(this.image).removeClass('hidden');
            jQuery(this.video).addClass('hidden');
            jQuery(this.video).hide();
            this.activeDetection = false;
            return this.savePhoto(imgBase64);
        },

        getImageFromVideo: function getImageFromVideo() {
            const canvas = document.createElement('canvas');
            canvas.width = this.video.videoWidth;
            canvas.height = this.video.videoHeight;
            canvas.getContext('2d')
                .drawImage(this.video, 0, 0, canvas.width, canvas.height);
            return canvas.toDataURL().replace('data:image/png;base64,', '');
        },

        savePhoto: function(photo){
            jQuery('body').trigger('processStart');
            var urlPhoto = jQuery('#urlphoto').val();
            var photoMediaUrl;
            jQuery.ajax({
                url: urlPhoto,
                type: "POST",
                data: {
                    photo: photo,
                    current:jQuery('input[name="customer[photo]"]').val()
                },
                dataType: 'json',
                success: function (data) {
                    jQuery('input[name="customer[photo]"]').val(data.path_photo).trigger('change');
                    photoMediaUrl = location.protocol+'//'+location.hostname+'/pub/media'+data.path_photo;
                    jQuery('body').trigger('processStop');
                },
                error: function (request, error) {
                    jQuery('body').trigger('processStop');
                }
            });
        },

        initFaceDetection: async function initFaceDetection() {
            const displaySize = {
                width: this.video.clientWidth,
                height: this.video.clientHeight
            };
            var detection = null;
            var resizedDetections;
            var self = this;

            this.canvas = faceApi.createCanvasFromMedia(this.video);
            // this.canvas.style.position = 'absolute';
            this.video.parentElement.append(this.canvas)

            faceApi.matchDimensions(this.canvas, displaySize);
            while (this.activeDetection) {
                detection = await faceApi.detectSingleFace(self.video);
                if (detection && detection.score > 0.96) {
                    self.drawBorder('green')
                } else {
                    self.drawBorder('red')
                }
            }
        },

        initVideo: function initVideo() {
            var self = this;
            this.activeDetection = true;
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
            } else if (navigator.mediaDevices) {
                navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
                    self.video.srcObject = stream;
                    self.video.addEventListener('playing', () => {
                        self.initFaceDetection()
                    });
                })
            } else {
                console.error('camera is not supported')
            }
        },

        drawBorder: function drawBorder(color) {
            if (this.video) {
                this.video.style.border = '5px solid ' + color
            }
        },

        initApp: function initApp() {
            this.active_video=true;
            this.hideImg();
            var self = this;
            var mediaUrl = location.protocol+'//'+location.hostname+'/pub/media';

            return Promise.all([
                faceApi.nets.tinyFaceDetector.loadFromUri(mediaUrl + '/models'),
                faceApi.nets.ssdMobilenetv1.loadFromUri(mediaUrl + '/models'),
                faceApi.nets.faceRecognitionNet.loadFromUri(mediaUrl+ '/models')
            ]).then(function () {
                self.video = document.getElementById('customer-video');
                self.video.style.border = '5px solid gray'
                self.initVideo();
            });
        }
    });
});
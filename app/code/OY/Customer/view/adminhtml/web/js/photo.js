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
            
            jQuery(document).on('click', '#enable-camera', _.bind(this.initApp, this));
            jQuery(document).on('click', '#made_photo', _.bind(this.takePhoto, this));
            jQuery(document).on('click', '#reload-video', _.bind(this.reloadVideo, this));
            _.delay(this.hideFields, 1500);
            
            jQuery(document).on('click',function (){
                if(jQuery('div[data-index="photo"]').hasClass('_error')){
                    jQuery('.cl_photo-error').show();
                }else{
                    jQuery('.cl_photo-error').hide();
                }
            })
            return this;
        },
        
        reloadVideo: function reloadVideo() {
            jQuery(this.image).addClass('hidden');
            jQuery(this.video).removeClass('hidden');
        },

        takePhoto: function takePhoto() {
            var imgBase64 = this.getImageFromVideo();
            this.image = document.getElementById('customer-image');
            jQuery(this.image).attr('width', this.video.clientWidth);
            jQuery(this.image).attr('height', this.video.clientHeight);
            jQuery(this.image).attr('src', 'data:image/png;base64, ' + imgBase64);
            jQuery(this.canvas).remove();
            jQuery(this.image).removeClass('hidden');
            jQuery(this.video).addClass('hidden');
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
            this.canvas.style.position = 'absolute';
            this.video.parentElement.append(this.canvas)

            faceApi.matchDimensions(this.canvas, displaySize);

            setInterval(async () => {
                detection = await faceApi.detectSingleFace(self.video);
                self.canvas.getContext('2d').clearRect(0, 0, self.canvas.width, self.canvas.height);
                
                if (detection && detection.score > 0.96) {
                    resizedDetections = faceApi.resizeResults([detection], displaySize);
                    faceApi.draw.drawDetections(self.canvas, resizedDetections);
                }
                if (detection && detection.score > 0.96) {
                    self.faceDetected = true;
                } else {
                    self.faceDetected = false;
                }
            }, 100);
        },

        initVideo: function initVideo() {
            var self = this;

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
        },

        initApp: function initApp() {
            var self = this;
            var mediaUrl = location.protocol+'//'+location.hostname+'/pub/media';
            
            return Promise.all([
                faceApi.nets.tinyFaceDetector.loadFromUri(mediaUrl + '/models'),
                faceApi.nets.ssdMobilenetv1.loadFromUri(mediaUrl + '/models'),
                faceApi.nets.faceRecognitionNet.loadFromUri(mediaUrl+ '/models')
            ]).then(function () {
                self.video = document.getElementById('customer-video');
                self.initVideo();
            });
        }
    });
});

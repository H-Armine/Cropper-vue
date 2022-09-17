<template>
    <vue-cropper
        ref="cropper"
        :src="imageSrc"
        :containerStyle="containerStyle"
        :view-mode="2"
        drag-mode="crop"
        :auto-crop-area="0.5"
        :min-container-width="250"
        :min-container-height="180"
        :background="true"
        :rotatable="true"
        alt="Source Image"
        @ready=""
        @cropstart=""
        @cropmove=""
        @cropend="getCroppedImage"
        @crop=""
        @zoom=""
    >
    </vue-cropper>
    <div class="text-red-900">
        {{cropError}}
    </div>
    <input type="file" name="image" @change="getImage">
    <button @click="cropImage" v-if="imageSrc !== ''" style="margin-right: 40px;">Crop</button>
    <div class="image-foreach">
        <img v-for="image in images"
             :src="image.name"
             alt=""
             class="w-[10%] h-[10%] m-2 object-fill"
        >
    </div>
</template>

<script>
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
export default {
    name: "ImageCropper",
    components: { VueCropper },
    data() {
        return {
            loading: false,
            imageSrc: '',
            show: false,
            containerStyle: {
                width: '400px'
            },
            imgStyle: {
                viewMode: 1,
                dragMode: 'crop',
                modal: true
            },
            cropImg: null,
            cropError: ''

        }
    },
    props:{
        images:{
            type : Array
        },
        default: []
    },
    methods: {
        openModal() {
            this.show = true;
            axios.post('', {}).then(res => {

            });
        },
        getCroppedImage(event) {

        },

        getImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = (event) => {
                this.imageSrc = event.target.result;
                this.$refs.cropper.replace(event.target.result);
            };
            reader.readAsDataURL(file)
        },

        cropImage(){
            this.$refs.cropper.getCroppedCanvas().toBlob(blob => {
                let formData = new FormData();
                formData.append('image', blob);
                axios.post("store",
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(res => {

                    this.images.push(res.data.file);
                }).catch(res=> {
                    this.cropError = res.response.data.message
                    console.log(res.response.data.message)
                });
            })
        }
    },
    created() {

    },
    mounted() {
        this.imageSrc = '';
    }
}


</script>

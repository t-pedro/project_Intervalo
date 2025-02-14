<template>
        <div class="tinymce-container" :style="{width:containerWidth}">
            <textarea :id="tinymceId" class="tinymce-textarea" />
        </div>
</template>

<script>
    import editorImage from './components/EditorImage'
    import plugins from './plugins'
    import toolbar from './toolbar'
    import load from './dynamicLoadScript'


    const tinymceCDN = 'https://cdn.jsdelivr.net/npm/tinymce-all-in-one@4.9.3/tinymce.min.js'

    export default {
        props: {
            id: {
                type: String,
                default: function() {
                return 'vue-tinymce-' + +new Date() + ((Math.random() * 1000).toFixed(0) + '')
                }
            },
            value: {
                type: String,
                default: ''
            },
            toolbar: {
                type: Array,
                required: false,
                default() {
                return []
                }
            },
            menubar: {
                type: String,
                default: 'file edit insert view format table'
            },
            height: {
                type: [Number, String],
                required: false,
                default: 360
            },
            width: {
                type: [Number, String],
                required: false,
                default: 'auto'
            }
        },

        components: {
            editorImage            
        },

        data(){
            return{
                hasChange: false,
                hasInit: false,
                tinymceId: this.id,
                fullscreen: false,
                languageTypeList: {
                    'en': 'en',
                    'zh': 'zh_CN',
                    'es': 'es_MX',
                    'ja': 'ja'
                }
            }
        },

        computed: {
            containerWidth() {
            const width = this.width
            if (/^[\d]+(\.[\d]+)?$/.test(width)) { // matches `100`, `'100'`
                return `${width}px`
            }
            return width
            }
        },

        watch: {
            value(val) {
            if (!this.hasChange && this.hasInit) {
                this.$nextTick(() =>
                window.tinymce.get(this.tinymceId).setContent(val || ''))
            }
            }
        },

        mounted() {
            this.init()
        },

        activated() {
            if (window.tinymce) {
            this.initTinymce()
            }
        },

        deactivated() {
            this.destroyTinymce()
        },

        destroyed() {
            this.destroyTinymce()
        },

        methods:{

            init() {
                // dynamic load tinymce from cdn
                load(tinymceCDN, (err) => {
                    if (err) { this.$message.error(err.message)
                               return }

                    this.initTinymce()
                })
            },
            initTinymce() {
                const _this = this

                window.tinymce.init({
                    selector: `#${this.tinymceId}`,
                    branding: false,
                    language: this.languageTypeList['es'],
                    height:   this.height,
                    body_class: 'panel-body',
                    object_resizing: false,
                    toolbar: this.toolbar.length > 0 ? this.toolbar : toolbar,
                    menubar: "",
                    plugins: plugins,
                    end_container_on_empty_block: true,
                    powerpaste_word_import: 'clean',
                    code_dialog_height: 450,
                    code_dialog_width: 1000,
                    advlist_bullet_styles: 'square',
                    advlist_number_styles: 'default',
                    imagetools_cors_hosts: ['www.tinymce.com', 'codepen.io'],
                    default_link_target: '_blank',
                    link_title: false,
                    nonbreaking_force_tab: true, // inserting nonbreaking space &nbsp; need Nonbreaking Space Plugin
                    init_instance_callback: editor => {
                        if (_this.value) {
                            editor.setContent(_this.value)
                        }
                        _this.hasInit = true
                        editor.on('NodeChange Change KeyUp SetContent', () => {
                            this.hasChange = true
                            
                            let content = editor.getContent()
                            this.$emit('update:modelValue', content)
                            // this.$emit('input', content )
                        })
                    },

                    convert_urls: false
                })
            },
            destroyTinymce() {
                const tinymce = window.tinymce.get(this.tinymceId)
                // if (this.fullscreen) {
                //     tinymce.execCommand('mceFullScreen')
                // }
                if (tinymce) {
                    tinymce.destroy()
                }
            },
            setContent(value) {
                window.tinymce.get(this.tinymceId).setContent(value)
            },
            getContent() {
                window.tinymce.get(this.tinymceId).getContent()
            },
            imageSuccessCBK(arr) {
                arr.forEach(v => window.tinymce.get(this.tinymceId).insertContent(`<img class="wscnph" src="${v.url}" >`))
            },
        }
    }
</script>


<style scoped>

</style>
<template>
    <v-card flat class="site-config-page">
        <div class="page-heading">
            <div>
                <span>Sitio público</span>
                <h2>Marca, textos y colores del Aplicativo</h2>
                <p>
                    Controla lo que ve el cliente: título, frases comerciales, WhatsApp, correo,
                    beneficios y paleta visual sin tocar código.
                </p>
            </div>

            <v-btn rounded depressed color="primary" :loading="guardando" @click="guardarConfiguracion">
                <v-icon left>mdi-content-save-outline</v-icon>
                Guardar cambios
            </v-btn>
        </div>

        <v-row>
            <v-col cols="12" lg="7">
                <v-card outlined class="form-card">
                    <v-card-title>Contenido principal</v-card-title>
                    <v-card-text>
                        <v-form ref="formulario">
                            <v-row dense>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model.trim="form.nombre_sitio" label="Nombre del sitio" outlined dense rounded
                                        prepend-inner-icon="mdi-storefront-outline" :rules="[rules.required]" />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-text-field v-model.trim="form.eyebrow" label="Texto superior" outlined dense rounded
                                        prepend-inner-icon="mdi-label-outline" />
                                </v-col>

                                <v-col cols="12">
                                    <v-text-field v-model.trim="form.titulo" label="Título grande del hero" outlined dense rounded
                                        prepend-inner-icon="mdi-format-title" :rules="[rules.required]" />
                                </v-col>

                                <v-col cols="12">
                                    <v-textarea v-model.trim="form.descripcion" label="Descripción principal" outlined rounded
                                        rows="3" prepend-inner-icon="mdi-text-box-outline" />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-text-field v-model.trim="form.titulo_productos" label="Título productos" outlined dense rounded />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-text-field v-model.trim="form.subtitulo_productos" label="Etiqueta productos" outlined dense rounded />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-text-field v-model.trim="form.titulo_beneficios" label="Título beneficios" outlined dense rounded />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-text-field v-model.trim="form.titulo_contacto" label="Título contacto" outlined dense rounded />
                                </v-col>

                                <v-col cols="12">
                                    <v-textarea v-model.trim="form.descripcion_beneficios" label="Descripción beneficios" outlined rounded rows="2" />
                                </v-col>

                                <v-col cols="12">
                                    <v-textarea v-model.trim="form.descripcion_contacto" label="Descripción contacto" outlined rounded rows="2" />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-text-field v-model.trim="form.telefono_whatsapp" label="WhatsApp" outlined dense rounded
                                        prepend-inner-icon="mdi-whatsapp" />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-text-field v-model.trim="form.correo_contacto" label="Correo comercial" outlined dense rounded
                                        prepend-inner-icon="mdi-email-outline" :rules="[rules.emailOpcional]" />
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                </v-card>

                <v-card outlined class="form-card mt-4">
                    <v-card-title>
                        Beneficios comerciales
                        <v-spacer />
                        <v-btn small rounded outlined color="primary" @click="agregarBeneficio">
                            <v-icon left small>mdi-plus</v-icon>
                            Agregar
                        </v-btn>
                    </v-card-title>

                    <v-card-text>
                        <v-row v-for="(beneficio, index) in form.beneficios" :key="index" dense class="benefit-row">
                            <v-col cols="12" md="3">
                                <v-text-field v-model.trim="beneficio.icon" label="Ícono MDI" outlined dense rounded />
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field v-model.trim="beneficio.title" label="Título" outlined dense rounded />
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field v-model.trim="beneficio.text" label="Texto" outlined dense rounded />
                            </v-col>
                            <v-col cols="12" md="1" class="text-center">
                                <v-btn icon color="error" @click="quitarBeneficio(index)">
                                    <v-icon>mdi-delete-outline</v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" lg="5">
                <v-card outlined class="preview-card" :style="previewStyle">
                    <div class="preview-grid"></div>
                    <div class="preview-content">
                        <span>{{ form.eyebrow }}</span>
                        <h1>{{ form.titulo }}</h1>
                        <p>{{ form.descripcion }}</p>
                        <div class="preview-actions">
                            <v-btn rounded depressed :color="form.colores.acento" dark>Ver productos</v-btn>
                            <v-btn rounded outlined color="white">Catálogo</v-btn>
                        </div>
                    </div>
                </v-card>

                <v-card outlined class="form-card mt-4">
                    <v-card-title>Colores de marca</v-card-title>
                    <v-card-text>
                        <v-row dense>
                            <v-col v-for="color in colores" :key="color.key" cols="12" sm="6">
                                <label class="color-label">{{ color.label }}</label>
                                <div class="color-control">
                                    <input v-model="form.colores[color.key]" type="color">
                                    <v-text-field v-model.trim="form.colores[color.key]" dense outlined rounded hide-details />
                                </div>
                            </v-col>
                        </v-row>
                        <v-alert text type="info" class="mt-4 mb-0">
                            Dejé los colores actuales como base. Si el cliente quiere otra marca, se cambia acá.
                        </v-alert>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
export default {
    layout: 'parametrizacion',

    data() {
        return {
            guardando: false,
            form: this.formInicial(),
            colores: [
                { key: 'primario', label: 'Azul principal' },
                { key: 'secundario', label: 'Turquesa industrial' },
                { key: 'acento', label: 'Botones' },
                { key: 'fondo', label: 'Fondo suave' },
            ],
            rules: {
                required: value => !!value || 'Este campo es obligatorio',
                emailOpcional: value => !value || /.+@.+\..+/.test(value) || 'Correo inválido',
            },
        }
    },

    computed: {
        previewStyle() {
            return {
                background: `radial-gradient(circle at 82% 16%, ${this.form.colores.secundario}55, transparent 30%), linear-gradient(120deg, ${this.form.colores.primario}, ${this.form.colores.secundario})`,
            }
        },
    },

    mounted() {
        this.cargarConfiguracion()
    },

    methods: {
        formInicial() {
            return {
                nombre_sitio: 'Embacolsa',
                eyebrow: 'EMPAQUES, EMBALAJES Y SUMINISTROS',
                titulo: 'Embacolsa',
                descripcion: 'Soluciones listas para empacar, proteger y despachar tu operación con mejor imagen, menos vueltas y respuesta comercial rápida.',
                titulo_productos: 'Una muestra por cada categoría activa',
                subtitulo_productos: 'Catálogo parametrizado',
                titulo_beneficios: 'Acompañamiento para comprar mejor, no solo comprar más.',
                descripcion_beneficios: 'Te ayudamos a escoger materiales según carga, rotación, presupuesto y presentación final.',
                titulo_contacto: 'Cuéntanos qué necesitas empacar.',
                descripcion_contacto: 'Armamos una cotización clara para tu empresa.',
                telefono_whatsapp: '573218720375',
                correo_contacto: 'comercial@embacolsa.com.co',
                beneficios: [
                    { icon: 'mdi-truck-fast-outline', title: 'Despacho confiable', text: 'Coordinación para que tu operación no se frene.' },
                    { icon: 'mdi-account-tie-outline', title: 'Asesoría real', text: 'Recomendaciones según el producto y el volumen.' },
                    { icon: 'mdi-layers-triple-outline', title: 'Portafolio completo', text: 'Compra lo esencial desde un solo proveedor.' },
                    { icon: 'mdi-message-reply-text-outline', title: 'Atención directa', text: 'Cotizaciones claras y respuesta sin tanta vuelta.' },
                ],
                colores: {
                    primario: '#0f2c61',
                    secundario: '#0d7880',
                    acento: '#1e88e5',
                    fondo: '#f4f8fb',
                },
                estado: true,
            }
        },

        async cargarConfiguracion() {
            try {
                const response = await this.$axios.get('/sitio-publico/configuracion')
                this.form = {
                    ...this.formInicial(),
                    ...(response.data || {}),
                    colores: {
                        ...this.formInicial().colores,
                        ...(response.data?.colores || {}),
                    },
                    beneficios: response.data?.beneficios?.length
                        ? response.data.beneficios
                        : this.formInicial().beneficios,
                }
            } catch (error) {
                this.$toast.error('No se pudo cargar la configuración del sitio.')
            }
        },

        agregarBeneficio() {
            this.form.beneficios.push({
                icon: 'mdi-star-outline',
                title: '',
                text: '',
            })
        },

        quitarBeneficio(index) {
            this.form.beneficios.splice(index, 1)
        },

        async guardarConfiguracion() {
            if (!this.$refs.formulario.validate()) {
                return
            }

            try {
                this.guardando = true
                await this.$axios.post('/sitio-publico/configuracion', this.form)
                this.$toast.success('Sitio público actualizado.')
            } catch (error) {
                this.$toast.error('No se pudo guardar la configuración.')
            } finally {
                this.guardando = false
            }
        },
    },
}
</script>

<style scoped>
.site-config-page {
    background: transparent;
}

.page-heading,
.form-card,
.preview-card {
    border-radius: 14px !important;
}

.page-heading {
    align-items: center;
    background: #fff;
    border: 1px solid #dfe8f0;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    margin-bottom: 18px;
    padding: 24px;
}

.page-heading span {
    color: #0d7880;
    font-size: 11px;
    font-weight: 950;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.page-heading h2 {
    color: #14325f;
    font-size: 28px;
    font-weight: 950;
    margin: 4px 0;
}

.page-heading p {
    color: #65758d;
    margin: 0;
    max-width: 720px;
}

.benefit-row {
    border-bottom: 1px solid #edf2f7;
    margin-bottom: 10px;
    padding-bottom: 6px;
}

.preview-card {
    color: #fff;
    min-height: 420px;
    overflow: hidden;
    padding: 36px;
    position: sticky;
    top: 22px;
}

.preview-grid {
    background-image:
        linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px);
    background-size: 36px 36px;
    inset: 0;
    mask-image: linear-gradient(90deg, #000, transparent);
    opacity: .55;
    position: absolute;
}

.preview-content {
    position: relative;
    z-index: 2;
}

.preview-content span {
    color: #72edf0;
    font-size: 12px;
    font-weight: 950;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.preview-content h1 {
    font-size: clamp(46px, 7vw, 82px);
    font-weight: 950;
    line-height: .95;
    margin: 18px 0;
}

.preview-content p {
    color: rgba(255,255,255,.86);
    font-size: 18px;
    line-height: 1.7;
}

.preview-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 26px;
}

.color-label {
    color: #50627a;
    display: block;
    font-size: 12px;
    font-weight: 900;
    margin-bottom: 7px;
}

.color-control {
    align-items: center;
    display: flex;
    gap: 10px;
}

.color-control input[type='color'] {
    border: 0;
    border-radius: 999px;
    height: 42px;
    overflow: hidden;
    width: 52px;
}
</style>

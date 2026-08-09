<template>
    <div class="login-container">
        <div class="login-overlay"></div>
        <div class="login-glow login-glow-one"></div>
        <div class="login-glow login-glow-two"></div>

        <v-container class="login-shell">
            <v-row align="center" justify="center" class="login-row">
                <v-col cols="12" lg="7" class="login-brand-col">
                    <section class="brand-panel">
                        <div class="brand-kicker">
                            <v-icon size="19" color="#67f0ea" class="mr-2">
                                mdi-shield-check-outline
                            </v-icon>
                            Plataforma comercial Embacolsa
                        </div>

                        <h1 class="brand-title">
                            Empaques, embalajes y control para operar sin vueltas.
                        </h1>

                        <p class="brand-copy">
                            Administra productos, categorías, marcas e imágenes desde
                            un panel pensado para mantener tu catálogo listo y vendible.
                        </p>

                        <div class="brand-actions">
                            <div class="brand-pill">
                                <v-icon size="20" color="#1e88e5">
                                    mdi-package-variant-closed
                                </v-icon>
                                Catálogo dinámico
                            </div>

                            <div class="brand-pill">
                                <v-icon size="20" color="#00a884">
                                    mdi-account-cog-outline
                                </v-icon>
                                Gestión por usuarios
                            </div>
                        </div>

                        <div class="brand-visual">
                            <img src="/images/hero-products-optimized.webp" alt="Productos Embacolsa" />
                        </div>
                    </section>
                </v-col>

                <v-col cols="12" sm="10" md="8" lg="5" class="login-form-col">
                    <v-card class="login-card" elevation="0">
                        <v-card-text class="login-content">
                            <div class="login-header">
                                <img src="/images/embacolsa-optimized.webp" alt="Logo Embacolsa" class="login-logo" />

                                <div class="login-access-chip">
                                    Acceso seguro
                                </div>

                                <h2 class="login-title">
                                    Iniciar sesión
                                </h2>

                                <p class="login-subtitle mb-0">
                                    Ingresa tus credenciales para entrar al panel de
                                    parametrización y catálogo.
                                </p>
                            </div>

                            <v-form ref="formVal" lazy-validation @submit.prevent="iniciarSesion">
                                <v-row dense>
                                    <v-col cols="12">
                                        <v-text-field v-model.trim="form.email" label="Correo electrónico"
                                            prepend-inner-icon="mdi-email-outline" type="email" autocomplete="email"
                                            outlined rounded hide-details="auto" class="login-field"
                                            :rules="[rules.requerido, rules.email]" />
                                    </v-col>

                                    <v-col cols="12">
                                        <v-text-field v-model="form.password" label="Contraseña"
                                            prepend-inner-icon="mdi-lock-outline" :append-icon="mostrarPassword
                                                ? 'mdi-eye-off-outline'
                                                : 'mdi-eye-outline'
                                                " :type="mostrarPassword ? 'text' : 'password'"
                                            autocomplete="current-password" outlined rounded hide-details="auto"
                                            class="login-field" :rules="[rules.requerido]"
                                            @click:append="mostrarPassword = !mostrarPassword" />
                                    </v-col>

                                    <v-col cols="12" class="pt-3">
                                        <v-btn type="submit" block x-large rounded :loading="cargando"
                                            :disabled="cargando" class="login-button text-none">
                                            Entrar al panel
                                            <v-icon right size="22">
                                                mdi-arrow-right
                                            </v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>

                            <div class="login-help-row">
                                <v-btn text small type="button" class="text-none login-link" @click.stop="modal = true">
                                    ¿Olvidaste tu contraseña?
                                </v-btn>

                                <span>Soporte Softnova</span>
                            </div>

                            <div class="login-footer">
                                <span>© {{ new Date().getFullYear() }} SOFTNOVA</span>
                                <span>SOLUTIONS</span>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>

        <!-- Modal recuperación -->
        <v-dialog v-model="modal" max-width="560" persistent>
            <v-card class="modal-card">
                <!-- Encabezado -->
                <v-card-title class="modal-header white--text pa-5">
                    <div class="d-flex align-center">
                        <v-icon color="white" size="30" class="mr-3">
                            mdi-lock-reset
                        </v-icon>

                        <span class="font-weight-bold">
                            Recuperar contraseña
                        </span>
                    </div>

                    <v-spacer />

                    <v-btn icon color="white" type="button" @click="modal = false">
                        <v-icon size="34">
                            mdi-close
                        </v-icon>
                    </v-btn>
                </v-card-title>

                <!-- Contenido -->
                <v-card-text class="pa-6 pa-sm-8 text-center">
                    <!-- Los dos íconos separados -->
                    <v-row dense justify="center" align="center" class="mb-7">
                        <v-col cols="auto" class="px-2">
                            <v-avatar size="88" color="#25D366" class="elevation-5">
                                <v-icon color="white" size="52">
                                    mdi-whatsapp
                                </v-icon>
                            </v-avatar>
                        </v-col>

                        <v-col cols="auto" class="px-2">
                            <v-sheet width="145" height="88" color="#080a10" elevation="5" rounded="xl"
                                class="d-flex align-center justify-center pa-2">
                                <v-img src="/images/softnova-optimized.webp" alt="Softnova Solutions" contain max-width="130"
                                    max-height="76" />
                            </v-sheet>
                        </v-col>
                    </v-row>

                    <h2 class="modal-title mb-4">
                        ¿Necesitas recuperar tu contraseña?
                    </h2>

                    <p class="modal-description mb-6">
                        Para recuperar el acceso a tu cuenta, comunícate con
                        nuestra línea de soporte de
                        <strong class="primary--text">
                            Softnova Solutions
                        </strong>.
                    </p>

                    <div class="d-flex justify-center mb-6">
                        <v-chip large outlined color="primary" class="font-weight-bold px-5">
                            <v-icon left color="primary">
                                mdi-phone
                            </v-icon>

                            {{ telefonoSoporteVisible }}
                        </v-chip>
                    </div>

                    <p class="grey--text text--darken-1 mb-0">
                        Presiona el botón para comunicarte directamente con
                        nuestro equipo de soporte por WhatsApp.
                    </p>
                </v-card-text>

                <!-- Acciones -->
                <v-card-actions class="pa-5 pt-0">
                    <v-row dense>
                        <v-col cols="12" sm="5">
                            <v-btn block text rounded color="error" type="button" class="text-none font-weight-bold"
                                @click="modal = false">
                                Cancelar
                            </v-btn>
                        </v-col>

                        <v-col cols="12" sm="7">
                            <v-btn block rounded dark large color="#25D366" type="button"
                                class="text-none font-weight-bold" @click="abrirWhatsApp">
                                <v-icon left>
                                    mdi-whatsapp
                                </v-icon>

                                Contactar soporte
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    name: 'LoginPage',

    layout: 'login',

    data() {
        return {
            mostrarPassword: false,
            cargando: false,
            modal: false,

            telefonoSoporte: '573122891144',
            telefonoSoporteVisible: '+57 312 289 1144',

            form: {
                email: null,
                password: null,
            },

            rules: {
                requerido: value =>
                    !!value || 'Este campo es obligatorio',

                email: value => {
                    if (!value) {
                        return 'Este campo es obligatorio'
                    }

                    const formato = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

                    return (
                        formato.test(value) ||
                        'Ingresa un correo electrónico válido'
                    )
                },
            },
        }
    },

    methods: {
        async iniciarSesion() {
            const formularioValido = this.$refs.formVal.validate()

            if (!formularioValido) {
                this.$toast.warning(
                    'Por favor, completa todos los campos correctamente antes de continuar.'
                )

                return
            }

            try {
                this.cargando = true

                const response = await this.$axios.post('/auth/login', {
                    email: this.form.email,
                    password: this.form.password,
                })

                sessionStorage.setItem(
                    'embacolsa_token',
                    response.data.access_token
                )

                this.$router.push('/modulo-parametrizacion')

                this.$toast.success('¡Bienvenido!')
                /*
                const response = await this.$axios.post('/login', {
                    email: this.form.email,
                    password: this.form.password,
                })

                sessionStorage.setItem(
                    'embacolsa_token',
                    response.data.token
                )

                await this.$router.push('/')
                */
            } catch (error) {
                console.error('Error al iniciar sesión:', error)

                this.$toast.error(
                    'Error al iniciar sesión. Verifica tus credenciales e intenta nuevamente.'
                )
            } finally {
                this.cargando = false
            }
        },

        abrirWhatsApp() {
            const correo = this.form.email || 'No especificado'

            const mensaje = [
                'Hola, equipo de soporte Softnova Solutions.',
                '',
                'Necesito ayuda para recuperar la contraseña de mi cuenta.',
                `Correo registrado: ${correo}`,
            ].join('\n')

            const url =
                `https://wa.me/${this.telefonoSoporte}` +
                `?text=${encodeURIComponent(mensaje)}`

            window.open(
                url,
                '_blank',
                'noopener,noreferrer'
            )
        },
    },
}
</script>

<style scoped>
.login-container {
    align-items: center;
    background:
        radial-gradient(circle at 15% 18%, rgba(103, 240, 234, .22), transparent 28%),
        radial-gradient(circle at 82% 12%, rgba(31, 133, 221, .22), transparent 30%),
        linear-gradient(135deg, #061d3a 0%, #073d59 48%, #0a6c74 100%);
    display: flex;
    overflow: hidden;
    justify-content: center;
    min-height: 100vh;
    padding: 34px 18px;
    position: relative;
    width: 100%;
}

.login-overlay {
    background:
        linear-gradient(90deg, rgba(3, 20, 48, .94), rgba(5, 39, 70, .78), rgba(3, 77, 85, .66)),
        url('/images/login-optimized.webp') center center / cover no-repeat;
    inset: 0;
    opacity: .74;
    position: absolute;
}

.login-glow {
    border-radius: 999px;
    filter: blur(5px);
    position: absolute;
}

.login-glow-one {
    background: rgba(77, 222, 215, .16);
    height: 380px;
    left: -120px;
    top: -110px;
    width: 380px;
}

.login-glow-two {
    background: rgba(31, 132, 229, .17);
    bottom: -160px;
    height: 440px;
    right: -135px;
    width: 440px;
}

.login-shell {
    max-width: 1240px;
    position: relative;
    z-index: 2;
}

.login-row {
    min-height: calc(100vh - 68px);
}

.login-brand-col,
.login-form-col {
    position: relative;
    z-index: 2;
}

.brand-panel {
    color: #ffffff;
    min-height: 620px;
    overflow: hidden;
    padding: 36px 34px 28px 6px;
    position: relative;
}

.brand-kicker {
    align-items: center;
    color: #67f0ea;
    display: flex;
    font-size: 13px;
    font-weight: 900;
    letter-spacing: 2px;
    margin-bottom: 24px;
    text-transform: uppercase;
}

.brand-title {
    color: #ffffff;
    font-size: clamp(40px, 5.2vw, 72px);
    font-weight: 950;
    letter-spacing: -2.8px;
    line-height: .95;
    margin: 0;
    max-width: 780px;
    text-shadow: 0 22px 55px rgba(0, 0, 0, .28);
}

.brand-copy {
    color: rgba(255, 255, 255, .82);
    font-size: 18px;
    line-height: 1.75;
    margin: 26px 0 0;
    max-width: 570px;
}

.brand-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 30px;
}

.brand-pill {
    align-items: center;
    background: rgba(255, 255, 255, .94);
    border: 1px solid rgba(255, 255, 255, .46);
    border-radius: 999px;
    box-shadow: 0 18px 35px rgba(0, 19, 48, .22);
    color: #15345e;
    display: flex;
    font-size: 13px;
    font-weight: 900;
    gap: 9px;
    letter-spacing: .2px;
    padding: 11px 16px;
}

.brand-visual {
    bottom: -22px;
    display: none;
    filter: drop-shadow(0 34px 44px rgba(0, 0, 0, .38));
    left: 64px;
    max-width: 720px;
    pointer-events: none;
    position: absolute;
    width: min(65vw, 720px);
}

.brand-visual img {
    display: block;
    height: auto;
    width: 100%;
}

.login-card {
    background: rgba(255, 255, 255, .94) !important;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 34px !important;
    box-shadow:
        0 34px 90px rgba(0, 23, 58, 0.38),
        inset 0 1px 0 rgba(255, 255, 255, .7) !important;
    overflow: hidden;
    position: relative;
    width: 100%;
}

.login-card::before {
    background: linear-gradient(90deg, #1e88e5, #00a884, #f4c430);
    content: '';
    height: 7px;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}

.login-content {
    padding: 30px 38px 28px !important;
}

.login-header {
    align-items: center;
    display: flex;
    flex-direction: column;
    margin-bottom: 26px;
    text-align: center;
}

.login-logo {
    height: 152px;
    margin: -18px 0 -18px;
    max-width: 430px;
    object-fit: contain;
    filter:
        saturate(1.08) contrast(1.04) drop-shadow(0 14px 18px rgba(7, 38, 80, .13));
    width: 100%;
}

.login-access-chip {
    background: #eef8ff;
    border: 1px solid #d5ecff;
    border-radius: 999px;
    color: #1e75bd;
    font-size: 12px;
    font-weight: 950;
    letter-spacing: 1.6px;
    margin-bottom: 14px;
    padding: 7px 13px;
    text-transform: uppercase;
}

.login-title {
    color: #102d55;
    font-size: 34px;
    font-weight: 950;
    letter-spacing: -.7px;
    margin: 0;
}

.login-subtitle {
    color: #6a7890;
    font-size: 15px;
    line-height: 1.65;
    margin-top: 12px;
    max-width: 350px;
}

.login-field ::v-deep .v-input__slot {
    background: #f6f9fc !important;
    border-radius: 26px !important;
    box-shadow: none !important;
    min-height: 58px !important;
    padding: 0 16px !important;
}

.login-field ::v-deep fieldset {
    border-color: #dde8f3 !important;
    border-width: 1px !important;
}

.login-field ::v-deep .v-input--is-focused fieldset,
.login-field ::v-deep .v-input__slot:hover fieldset {
    border-color: #1e88e5 !important;
}

.login-field ::v-deep .v-icon,
.login-field ::v-deep .v-label--active {
    color: #1e88e5 !important;
}

.login-field ::v-deep input {
    color: #1c3049 !important;
    font-weight: 700;
}

.login-field ::v-deep .v-messages__message {
    color: #d34848 !important;
    font-weight: 500;
}

.login-button {
    background: linear-gradient(135deg, #0a66c2 0%, #118bd2 52%, #00a884 100%) !important;
    box-shadow: 0 18px 34px rgba(10, 102, 194, .28) !important;
    color: #ffffff !important;
    font-weight: 950;
    height: 56px !important;
    letter-spacing: .2px;
}

.login-help-row {
    align-items: center;
    color: #8997aa;
    display: flex;
    font-size: 12px;
    justify-content: space-between;
    margin-top: 18px;
}

.login-link {
    color: #0a66c2 !important;
    font-weight: 900;
}

.login-footer {
    align-items: center;
    border-top: 1px solid #e8eef5;
    color: #8997aa;
    display: flex;
    font-size: 11px;
    gap: 10px;
    justify-content: center;
    letter-spacing: 1.1px;
    margin-top: 22px;
    padding-top: 18px;
    text-transform: uppercase;
}

.login-footer img {
    height: 22px;
    object-fit: contain;
    width: auto;
}

/* Solo los estilos que Vuetify no cubre fácilmente */

.modal-card {
    border-radius: 24px !important;
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg,
            #0675bd 0%,
            #0698b3 55%,
            #43ad56 100%);
}

.modal-title {
    color: #263747;
    font-size: 25px;
    font-weight: 800;
    line-height: 1.3;
}

.modal-description {
    color: #647587;
    font-size: 17px;
    line-height: 1.7;
}

@media (max-width: 600px) {
    .login-container {
        padding: 18px 12px;
    }

    .login-row {
        min-height: auto;
    }

    .brand-panel {
        min-height: auto;
        padding: 14px 8px 6px;
        text-align: center;
    }

    .brand-kicker,
    .brand-actions {
        justify-content: center;
    }

    .brand-title {
        font-size: 34px;
        letter-spacing: -1.4px;
    }

    .brand-copy {
        font-size: 15px;
        margin-top: 16px;
    }

    .brand-visual {
        display: block;
        margin: 18px auto -18px;
        max-width: 420px;
        position: relative;
        left: auto;
        bottom: auto;
        width: 108%;
    }

    .login-content {
        padding: 26px 22px 24px !important;
    }

    .login-logo {
        height: 126px;
        margin: -16px 0 -12px;
    }

    .login-title {
        font-size: 29px;
    }

    .login-help-row {
        align-items: center;
        flex-direction: column;
        gap: 7px;
    }

    .modal-title {
        font-size: 21px;
    }

    .modal-description {
        font-size: 15px;
    }
}

@media (min-width: 601px) and (max-width: 1263px) {
    .brand-panel {
        min-height: 420px;
        padding: 18px 14px 0;
        text-align: center;
    }

    .brand-kicker,
    .brand-actions {
        justify-content: center;
    }

    .brand-copy {
        margin-left: auto;
        margin-right: auto;
    }

    .brand-visual {
        bottom: -42px;
        display: none;
        left: 50%;
        max-width: 620px;
        transform: translateX(-50%);
        width: 78%;
    }
}
</style>

<template>
    <div class="login-container">
        <div class="login-overlay"></div>

        <!-- Login -->
        <v-card class="login-card" elevation="16">
            <v-card-text class="login-content">
                <div class="login-header">
                    <img src="/images/embacolsa.png" alt="Logo Embacolsa" class="login-logo" />

                    <h1 class="login-title">
                        Inicio de sesión
                    </h1>

                    <p class="login-subtitle mb-0">
                        Bienvenido a tu sistema de gestión Embacolsa, por favor
                        ingresa tus credenciales para continuar.
                    </p>
                </div>

                <v-form ref="formVal" lazy-validation @submit.prevent="iniciarSesion">
                    <v-row dense>
                        <v-col cols="12">
                            <v-text-field v-model.trim="form.email" label="Correo electrónico"
                                prepend-inner-icon="mdi-email-outline" type="email" autocomplete="email" outlined
                                rounded dense hide-details="auto" class="login-field"
                                :rules="[rules.requerido, rules.email]" />
                        </v-col>

                        <v-col cols="12">
                            <v-text-field v-model="form.password" label="Contraseña"
                                prepend-inner-icon="mdi-lock-outline" :append-icon="mostrarPassword
                                    ? 'mdi-eye-off-outline'
                                    : 'mdi-eye-outline'
                                    " :type="mostrarPassword ? 'text' : 'password'" autocomplete="current-password"
                                outlined rounded dense hide-details="auto" class="login-field"
                                :rules="[rules.requerido]" @click:append="mostrarPassword = !mostrarPassword" />
                        </v-col>

                        <v-col cols="12">
                            <v-btn type="submit" block large rounded :loading="cargando" :disabled="cargando"
                                class="login-button text-none">
                                Iniciar sesión
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>

                <div class="text-center mt-4">
                    <v-btn text small type="button" class="text-none font-weight-bold white--text"
                        @click.stop="modal = true">
                        ¿Olvidaste tu contraseña?
                    </v-btn>
                </div>

                <div class="login-footer">
                    <strong>SOFTNOVA-SOLUTIONS</strong> © 2026
                </div>
            </v-card-text>
        </v-card>

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
                                <v-img src="/images/softnova.jpeg" alt="Softnova Solutions" contain max-width="130"
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
                    response.data.token
                )

                this.$router.push('/modulo-parametrizacion/categorias')

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
    position: relative;
    width: 100%;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background:
        url('/images/login.png') center center / cover no-repeat;
    overflow: hidden;
}

.login-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
            rgba(0, 32, 78, 0.28),
            rgba(0, 119, 168, 0.1),
            rgba(62, 160, 74, 0.08));
}

.login-card {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 440px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 28px !important;
    background: rgba(220, 223, 227, 0.75) !important;
    box-shadow:
        0 28px 65px rgba(0, 28, 68, 0.32),
        0 10px 25px rgba(0, 0, 0, 0.16) !important;
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
}

.login-content {
    padding: 18px 36px 30px !important;
}

.login-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 28px;
    text-align: center;
}

.login-logo {
    width: 520px;
    max-width: none;
    height: 230px;
    margin: -42px 0 -22px;
    object-fit: contain;
    transform: scale(1.12);
    mix-blend-mode: multiply;
    filter:
        brightness(1.55) contrast(1.08) saturate(1.2) drop-shadow(0 12px 22px rgba(0, 0, 0, 0.2));
}

.login-title {
    margin: 0;
    color: #0785c1;
    font-size: 31px;
    font-weight: 800;
    text-shadow: 0 3px 12px rgba(0, 26, 63, 0.45);
}

.login-subtitle {
    max-width: 325px;
    margin-top: 18px;
    color: #ffffff;
    font-size: 16px;
    line-height: 1.6;
    text-shadow: 0 2px 8px rgba(0, 26, 63, 0.4);
}

.login-field ::v-deep .v-input__slot {
    min-height: 52px !important;
    padding: 0 16px !important;
    background: rgba(255, 255, 255, 0.96) !important;
    border-radius: 26px !important;
    box-shadow: 0 8px 20px rgba(0, 31, 72, 0.14) !important;
}

.login-field ::v-deep fieldset {
    border-color: rgba(194, 204, 214, 0.95) !important;
}

.login-field ::v-deep .v-icon,
.login-field ::v-deep .v-label--active {
    color: #0785c1 !important;
}

.login-field ::v-deep input {
    color: #283849 !important;
}

.login-field ::v-deep .v-messages__message {
    color: #ffffff !important;
    font-weight: 500;
}

.login-button {
    height: 50px !important;
    color: #ffffff !important;
    font-weight: 700;
    background: linear-gradient(135deg,
            #0675bd 0%,
            #0698b3 55%,
            #43ad56 100%) !important;
    box-shadow: 0 14px 28px rgba(0, 28, 68, 0.3) !important;
}

.login-footer {
    margin-top: 24px;
    color: #0675bd;
    font-size: 11px;
    letter-spacing: 2px;
    text-align: center;
    opacity: 0.8;
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
        padding: 16px;
    }

    .login-content {
        padding: 16px 22px 26px !important;
    }

    .login-logo {
        width: 430px;
        height: 205px;
        margin: -36px 0 -18px;
        transform: scale(1.08);
    }

    .login-title {
        font-size: 26px;
    }

    .modal-title {
        font-size: 21px;
    }

    .modal-description {
        font-size: 15px;
    }
}
</style>
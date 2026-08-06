<template>
    <div class="toast-wrapper">
        <transition name="toast-slide">
            <v-alert v-if="visible" :key="toast.id" :type="toast.type" :class="[
                'app-toast',
                `app-toast--${toast.type}`
            ]" elevation="16" dismissible dark @input="cerrar">
                <div class="toast-content">
                    <strong class="toast-title">
                        {{ titulo }}
                    </strong>

                    <span class="toast-message">
                        {{ toast.message }}
                    </span>
                </div>
            </v-alert>
        </transition>
    </div>
</template>

<script>
import { toastBus } from '~/plugins/toast'

export default {
    name: 'AppToast',

    data() {
        return {
            visible: false,
            timer: null,

            toast: {
                id: null,
                type: 'info',
                message: '',
                timeout: 3500
            }
        }
    },

    computed: {
        titulo() {
            const titulos = {
                success: 'Proceso exitoso',
                warning: 'Atención',
                info: 'Información',
                error: '¡Ups!'
            }

            return titulos[this.toast.type] || 'Información'
        }
    },

    created() {
        toastBus.$on('toast:show', this.mostrar)
    },

    beforeDestroy() {
        toastBus.$off('toast:show', this.mostrar)

        if (this.timer) {
            clearTimeout(this.timer)
        }
    },

    methods: {
        mostrar(datos) {
            if (this.timer) {
                clearTimeout(this.timer)
            }

            this.toast = {
                id: datos.id,
                type: datos.type || 'info',
                message: datos.message,
                timeout: datos.timeout || 3500
            }

            this.visible = true

            this.timer = setTimeout(() => {
                this.cerrar()
            }, this.toast.timeout)
        },

        cerrar() {
            this.visible = false

            if (this.timer) {
                clearTimeout(this.timer)
                this.timer = null
            }
        }
    }
}
</script>

<style scoped>
.toast-wrapper {
    position: fixed;
    z-index: 999999;

    top: 24px;
    left: 50%;

    width: calc(100% - 32px);
    max-width: 480px;

    transform: translateX(-50%);

    pointer-events: none;
}

.app-toast {
    width: 100%;

    margin: 0 !important;
    padding: 16px 20px !important;

    color: #ffffff !important;

    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 18px !important;

    pointer-events: auto;

    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);

    box-shadow:
        0 18px 45px rgba(0, 28, 65, 0.3),
        0 6px 16px rgba(0, 0, 0, 0.18) !important;
}

/* Éxito */

.app-toast--success {
    background: linear-gradient(135deg,
            #137c4b 0%,
            #20a95f 55%,
            #45c96d 100%) !important;
}

/* Error */

.app-toast--error {
    background: linear-gradient(135deg,
            #a9192c 0%,
            #dc3545 55%,
            #f05b65 100%) !important;
}

/* Advertencia */

.app-toast--warning {
    background: linear-gradient(135deg,
            #c66a00 0%,
            #ef9700 55%,
            #ffb52e 100%) !important;
}

/* Información */

.app-toast--info {
    background: linear-gradient(135deg,
            #07549a 0%,
            #0789bd 55%,
            #10abc4 100%) !important;
}

.toast-content {
    display: flex;
    flex: 1;
    flex-direction: column;

    min-width: 0;
    padding-right: 4px;
}

.toast-title {
    margin-bottom: 4px;

    color: #ffffff !important;

    font-size: 15px;
    font-weight: 800;
    line-height: 1.3;
}

.toast-message {
    color: rgba(255, 255, 255, 0.94) !important;

    font-size: 13px;
    font-weight: 500;
    line-height: 1.45;

    word-break: break-word;
}

/* Estructura interna del v-alert */

.app-toast ::v-deep .v-alert__wrapper {
    display: flex;
    align-items: center;
}

/* Icono izquierdo */

.app-toast ::v-deep .v-alert__icon {
    flex-shrink: 0;

    margin-top: 0 !important;
    margin-right: 16px !important;

    color: #ffffff !important;
}

/* Contenido interno */

.app-toast ::v-deep .v-alert__content {
    flex: 1;
    min-width: 0;
}

/* Botón de cerrar */

.app-toast ::v-deep .v-alert__dismissible {
    align-self: center;
    flex-shrink: 0;

    margin-left: 16px !important;
}

.app-toast ::v-deep .v-alert__dismissible .v-icon {
    color: #ffffff !important;
}

/* Animación de entrada */

.toast-slide-enter-active {
    transition:
        opacity 0.35s ease,
        transform 0.42s cubic-bezier(0.22, 1, 0.36, 1);
}

/* Animación de salida */

.toast-slide-leave-active {
    transition:
        opacity 0.25s ease,
        transform 0.25s ease;
}

.toast-slide-enter {
    opacity: 0;
    transform: translateY(-45px) scale(0.94);
}

.toast-slide-leave-to {
    opacity: 0;
    transform: translateY(-25px) scale(0.96);
}

@media (max-width: 600px) {
    .toast-wrapper {
        top: 16px;

        width: calc(100% - 24px);
    }

    .app-toast {
        padding: 14px 15px !important;

        border-radius: 15px !important;
    }

    .app-toast ::v-deep .v-alert__icon {
        margin-right: 12px !important;
    }

    .app-toast ::v-deep .v-alert__dismissible {
        margin-left: 10px !important;
    }

    .toast-title {
        font-size: 14px;
    }

    .toast-message {
        font-size: 12px;
    }
}
</style>
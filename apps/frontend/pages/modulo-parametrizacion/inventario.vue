<template>  
    <v-card flat class="categories-page">

        <!-- Encabezado -->
        <div class="page-heading">
            <div>
                <span>Información</span>
                <h2>Inventario</h2> 
                <p>
                    Información general del inventario y sus movimientos
                </p>
            </div>
        </div>

        <!-- Tarjetas de resumen interactivas -->
        <v-row>
            <v-col v-for="card in tarjetasResumen" :key="card.title" cols="12" sm="6" lg="3">
                <v-card outlined class="sales-stat-card clickable-card"
                    :class="{ 'card-active': moduloActivo === card.component }" @click="moduloActivo = card.component">
                    <div class="sales-stat-icon" :class="card.class">
                        <v-icon color="white">{{ card.icon }}</v-icon>
                    </div>
                    <span>{{ card.title }}</span>
                    <strong>{{ card.value }}</strong>
                    <small>{{ card.help }}</small>
                </v-card>
            </v-col>
        </v-row>

        <!-- Renderizado dinámico del módulo seleccionado -->
        <div class="mt-6">
            <component :is="moduloActivo" />
        </div>

    </v-card>
</template>

<script>
import Ajustes from '../../components/modulo-parametrizacion/Inventario/ajustes.vue';
import Entradas from '../../components/modulo-parametrizacion/Inventario/entradas.vue';
import Salidas from '../../components/modulo-parametrizacion/Inventario/salidas.vue';
import Traslados from '../../components/modulo-parametrizacion/Inventario/traslados.vue';

export default {
    layout: 'parametrizacion',

    components: {
        Entradas,
        Salidas,
        Traslados,
        Ajustes
    },

    data() {
        return {
            moduloActivo: 'Entradas',
            resumenInventario: {
                totalEntradas: 145,
                totalSalidas: 320,
                totalAjustes: 12,
                totalTraslados: 8
            }
        }
    },

    computed: {
        tarjetasResumen() {
            return [
                {
                    title: 'Entradas de mercancía',
                    value: this.resumenInventario.totalEntradas,
                    help: 'Ingresos registrados este mes',
                    icon: 'mdi-invoice-arrow-left-outline',
                    class: 'blue',
                    component: 'Entradas'
                },
                {
                    title: 'Salidas / Ventas',
                    value: this.resumenInventario.totalSalidas,
                    help: 'Despachos por ventas y bajas del período',
                    icon: 'mdi-invoice-arrow-right-outline',
                    class: 'green',
                    component: 'Salidas'
                },
                {
                    title: 'Ajustes de stock',
                    value: this.resumenInventario.totalAjustes,
                    help: 'Correcciones por conteo, merma o daño',
                    icon: 'mdi-receipt-text-check-outline',
                    class: 'purple',
                    component: 'Ajustes'
                },
                {
                    title: 'Traslados inter-bodega',
                    value: this.resumenInventario.totalTraslados,
                    help: 'Movimientos de stock entre sedes',
                    icon: 'mdi-transit-transfer',
                    class: 'orange',
                    component: 'Traslados'
                },
            ]
        }
    }
}
</script>

<style scoped>
.categories-page {
    background: transparent;
}

.page-heading,
.panel-card {
    border-radius: 16px !important;
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
    line-height: 1.55;
    margin: 0;
}

.panel-card {
    border-color: #dfe8f0 !important;
    overflow: hidden;
}

.panel-card-title {
    align-items: center;
    gap: 14px;
    padding: 22px 24px;
}

.panel-title-icon {
    align-items: center;
    background: #eef6ff;
    border-radius: 14px;
    display: flex;
    height: 46px;
    justify-content: center;
    width: 46px;
}

.panel-card-title strong {
    color: #243b53;
    display: block;
    font-size: 19px;
}

.panel-card-title p {
    color: #65758d;
    font-size: 13px;
    margin: 2px 0 0;
}

.panel-search {
    max-width: 360px;
}

.panel-table {
    border-top: 1px solid #e6edf5;
}

.sales-page {
    background: transparent;
}

.sales-filter-card,
.sales-panel {
    border: 1px solid #dce8f5 !important;
    border-radius: 22px !important;
    box-shadow: 0 16px 38px rgba(6, 29, 67, .06) !important;
    padding: 22px;
}

.sales-stat-card {
    border: 1px solid #dce8f5 !important;
    border-radius: 22px !important;
    box-shadow: 0 16px 38px rgba(6, 29, 67, .06) !important;
    min-height: 174px;
    padding: 22px;
    position: relative;
    top: 0;
    background-color: #fff;
}

/* Interacción e imán visual */
.clickable-card {
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.clickable-card:hover {
    top: -4px;
    box-shadow: 0 14px 28px rgba(20, 50, 95, 0.12) !important;
}

.card-active {
    border: 2px solid #14325f !important;
    box-shadow: 0 10px 24px rgba(20, 50, 95, 0.14) !important;
}

.card-active .sales-stat-icon {
    transform: scale(1.06);
    transition: transform 0.25s ease;
}

.sales-stat-icon {
    align-items: center;
    border-radius: 18px;
    display: flex;
    height: 48px;
    justify-content: center;
    margin-bottom: 16px;
    width: 48px;
    transition: transform 0.25s ease;
}

.sales-stat-icon.blue {
    background: linear-gradient(135deg, #0b5ed7, #2da8ff);
}

.sales-stat-icon.green {
    background: linear-gradient(135deg, #0c7a4d, #19c463);
}

.sales-stat-icon.purple {
    background: linear-gradient(135deg, #5528b5, #8d5cff);
}

.sales-stat-icon.orange {
    background: linear-gradient(135deg, #e67800, #ffb020);
}

.sales-stat-card span,
.sales-stat-card small {
    color: #7487a3;
    display: block;
    font-weight: 800;
}

.sales-stat-card strong {
    color: #17365d;
    display: block;
    font-size: 28px;
    font-weight: 950;
    line-height: 1.1;
    margin: 8px 0;
}

.sales-panel-header {
    align-items: center;
    display: flex;
    gap: 16px;
    justify-content: space-between;
    margin-bottom: 18px;
}

.sales-panel-header span {
    color: #0d7880;
    font-size: 12px;
    font-weight: 950;
    letter-spacing: 1.4px;
    text-transform: uppercase;
}

.sales-panel-header h3 {
    color: #17365d;
    font-size: 24px;
    font-weight: 950;
    margin: 3px 0 0;
}

.sales-empty {
    color: #7487a3;
    padding: 46px 12px;
    text-align: center;
}

@media (max-width: 760px) {

    .page-heading,
    .panel-card-title {
        align-items: flex-start;
        flex-direction: column;
    }

    .panel-search {
        max-width: 100%;
        width: 100%;
    }
}
</style>

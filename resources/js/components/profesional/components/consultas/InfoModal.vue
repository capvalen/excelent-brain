<template>
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="infoModalLabel">Consulta Pendiente</h5>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>

                <div class="modal-body">
                    <table class="table table-striped">
                        <tbody v-if="dataModal">
                            <tr>
                                <td scope="row">ID:</td>
                                <td>{{ dataModal.id }}</td>
                            </tr>
                            
                            <tr>
                                <td scope="row">PACIENTE:</td>
                                <td>{{ dataModal.patient.name }}</td>
                            </tr>

                            <tr>
                                <td scope="row">FECHA:</td>
                                <td>{{ dataModal.date }}</td>
                            </tr>

                            <tr>
                                <td scope="row">HORA:</td>
                                <td>{{ horaHumana(dataModal.schedule.check_time) }} - {{ horaHumana(dataModal.schedule.departure_date ) }}</td>
                            </tr>

                            <tr>
                                <td scope="row">PAGO:</td>
                                <td>-</td>
                               <!--  <td>S/. {{ dataModal.payment ? dataModal.payment.price : '' }}</td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                data: this.dataModal
            }
        },

        methods: {
            horaHumana (hora) {
                let minutos = parseInt(hora.substring(3,5))
                hora = parseInt(hora.substring(0,2))
                if (hora > 12) {
                    return `${hora - 12}:${minutos.toString().length === 1 ? '0' + minutos : minutos} PM`
                } else {
                    if (hora === 12 && minutos >= 0) {
                        return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} PM`
                    }

                    if (hora === 0) {
                        return `12:${minutos.toString().length === 1 ? '0' + minutos : minutos} AM`
                    }
                    return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} AM`
                }
            },
        },

        props: {
            dataModal: Object
        }
    }
</script>
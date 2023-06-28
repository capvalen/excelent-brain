<template>
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="infoModalLabel">Consulta Pendiente</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>

                <div class="modal-body">
                    <table class="table table-hover">
                        <tbody v-if="dataModal">
                            <tr>
                                <td scope="row">ID:</td>
                                <td>{{ dataModal.id }}</td>
                            </tr>
                            
                            <tr>
                                <td scope="row">PACIENTE:</td>
                                <td class="text-capitalize">{{ dataModal.patient.name }}</td>
                            </tr>

                            <tr>
                                <td scope="row">FECHA:</td>
                                <td>{{ fechaLatam(dataModal.date) }}</td>
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
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
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
									return `${hora - 12}:${minutos.toString().length === 1 ? '0' + minutos : minutos} p.m.`
							} else {
									if (hora === 12 && minutos >= 0) {
											return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} p.m.`
									}

									if (hora === 0) {
											return `12:${minutos.toString().length === 1 ? '0' + minutos : minutos} a.m.`
									}
									return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} a.m.`
							}
					},
					fechaLatam(fecha){
						return moment(fecha).format('DD/MM/YYYY');
					},
        },

        props: {
            dataModal: Object
        }
    }
</script>
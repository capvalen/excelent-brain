<template>
    <main>
        <select
            @change="getMedicine"
            name=""
            class="form-select"
            id="selectMonth"
        >
            <option disabled selected value>Seleccione un mes</option>
            <option
                v-for="(month, index) in months"
                :key="index"
                :value="month"
            >
                {{ month | optionParseMonth }}
            </option>
        </select>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Medicamento</th>
                    <th>Cant. recetados</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(data, index) in dataMedicine" :key="index">
                    <th>{{ index + 1 }}</th>
                    <td>
                        {{ data.name }} - {{ data.tradename }} -
                        {{ data.concentration }}
                    </td>
                    <td>{{ data.sum }}</td>
                </tr>
            </tbody>
        </table>
    </main>
</template>

<script>
import moment from "moment";

export default {
    name: "ReporteMedicamentos",

    data() {
        return {
            dataMedicine: {},
            months: [],
        };
    },

    methods: {
        async getMedicine(e) {
            this.$swal({
                title: "Cargando...",
                showConfirmButton: false,
                icon: "info",
            });
            this.actual_month = e.target.value
            await this.axios
                .get(`/api/kairoPrescription/${this.actual_month}`)
                .then((res) => {
                    this.dataMedicine = res.data;
                    this.$swal.close();
                })
                .catch((err) => {
                    console.error(err);
                });
        },

        async getTotalMedicamentos(e) {
            this.$swal({
                title: "Cargando...",
                showConfirmButton: false,
                icon: "info",
            });
            this.actual_month = e.target.value;
            await this.axios
                .get(`/api/getPatientsMonth/${this.actual_month}`)
                .then((res) => {
                    this.dataPatients = res.data.pacientes;
                    this.total = res.data.totalPacientes;
                    this.$swal.close();
                })
                .catch((err) => {
                    console.error(err);
                });
        },

        setMonths() {
            this.months.push(moment(this.today).format("YYYY-MM"));
            while (moment(this.today).format("YYYY-MM") != "2022-01") {
                this.months.push(
                    moment(this.today).add(-1, "months").format("YYYY-MM")
                );
                this.today = moment(this.today).add(-1, "months");
            }
        },
    },

    filters: {
        optionParseMonth: function (val) {
            moment.locale("es");
            return moment(val).format("MMMM-YYYY");
        },
    },

    created() {
        // this.getMedicine();
        this.setMonths();
    },
};
</script>

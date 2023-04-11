<template>
    <div class="modal fade" id="faltasModal" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title" id="infoModalLabel">Ver Faltas</h5>
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
  
            <div class="modal-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Número de Faltas</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{dataPatient.faults}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
  </template>
  
  <script>
    export default {
      name: 'Faltas',
      props: {
        dataPatient: Object
      }
    }
  </script>
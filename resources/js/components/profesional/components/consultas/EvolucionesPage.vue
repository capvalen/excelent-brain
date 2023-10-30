<template>
	<div @click="clickOutside">
		<div class="d-flex justify-content-between align-items-center">
			<h4>Historia clínica del paciente</h4>

			<div class="" style="background-color: white; border-radius: 5px;">
				<button data-bs-toggle="modal" data-bs-target="#modalProximaCita" class="btn btn-outline-warning">
					<i class="fa-solid fa-person-walking-arrow-right"></i> Próxima cita
				</button>
				<router-link v-if="dataUser.profession!='Psicólogo'" :to="{ path: `/profesional/recetas/${datosConsulta.id}` }" class="btn btn-outline-secondary"
					title="Generar receta"><i class="fa-solid fa-vial"></i> Nueva receta
				</router-link>
				<button data-bs-toggle="modal" data-bs-target="#recetasModal" class="btn btn-outline-secondary">
					<i class="fa-solid fa-vial"></i> Ver recetas
				</button>
				<button v-if="datosConsulta.discharge != 1" class="btn btn-outline-success" @click="toDischarge">
					<i class="fa-solid fa-circle-check"></i> Dar de alta
				</button>
				<button v-else class="btn btn-outline-success" disabled>
					<i class="fa-solid fa-circle-check"></i> Dado de alta
				</button>
				<button data-bs-toggle="modal" data-bs-target="#modalVerTriajesViejos" class="btn btn-outline-info  ">
					<i class="fa-solid fa-scale-unbalanced-flip"></i> Ver triajes ({{ datosConsulta.triajes.length }})
				</button>
				<button data-bs-toggle="modal" data-bs-target="#examenModal" class="btn btn-outline-info ">
					<i class="fa-solid fa-note-sticky"></i> Ver exámenes ({{ contarExamenes()  }})
				</button>
				
			</div>
		</div>

		<div class="row mt-3">
			<div class="col-md-6">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-white"><i class="fas fa-user"></i> Datos personales del paciente</h6>
						<button class="btn btn-sm btn-outline-light" @click="datos1Paciente()"><i class="fa-regular fa-pen-to-square"></i></button>
						<!-- data-bs-toggle="modal" data-bs-target="#patientModal"  -->
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="historia-info">
							<p class="text-capitalize"><b>Nombre:</b> {{ datosConsulta ? lowerCase(datosConsulta.name) : '...' }}</p>
							<p><b>DNI:</b> {{ datosConsulta ? datosConsulta.dni : '...' }}</p>
							<p><b>Número de celular:</b> {{ datosConsulta.phone ? datosConsulta.phone : '...' }}</p>
							<p><b>Correo electrónico:</b> {{ datosConsulta.email ? datosConsulta.email : '...' }}</p>
							<p><b>Ocupación:</b> {{ datosConsulta ? datosConsulta.occupation : '...' }}</p>
							<p><b>Grado de instrucción:</b>
								<span v-if="datosConsulta.instruction_degree == 1">Inicial</span>
								<span v-else-if="datosConsulta.instruction_degree == 2">Primaria</span>
								<span v-else-if="datosConsulta.instruction_degree == 3">Secundaria</span>
								<span v-else-if="datosConsulta.instruction_degree == 4">Superior</span>
								<span v-else-if="datosConsulta.instruction_degree == 5">Técnico</span>
								<span v-else-if="datosConsulta.instruction_degree == 6">Sin Instrucción</span>
							</p>
							<p><b>Fecha de cumpleaños:</b> {{ datosConsulta ? fechaLatam(datosConsulta.birth_date) : '...' }} ({{
								datosConsulta ? calculateAge(datosConsulta.birth_date) + ' años' : '...' }})</p>
							<p><b>Estado civil:</b>
								<span v-if="datosConsulta.marital_status == 1">Soltero</span>
								<span v-else-if="datosConsulta.marital_status == 2">Casado</span>
								<span v-else-if="datosConsulta.marital_status == 3">Viudo</span>
								<span v-else-if="datosConsulta.marital_status == 4">Divorciado</span>
								<span v-else-if="datosConsulta.marital_status == 5">Conviviente</span>
							</p>
							<hr>
							<div v-if="datosConsulta.triajes.length>0">
								<p><strong>Triaje</strong></p>
								<div class="row row-cols-4" >
									<div class="col"><p><strong>TR:</strong> {{ datosConsulta.triajes[0].fc }}</p></div>
									<div class="col"><p><strong>FR:</strong> {{ datosConsulta.triajes[0].fr }}</p></div>
									<div class="col"><p><strong>PA:</strong> {{ datosConsulta.triajes[0].pa }}</p></div>
									<div class="col"><p><strong>T:</strong> {{ datosConsulta.triajes[0].t }}</p></div>
								</div>
								<p> <strong>Motivo:</strong> <span>{{ datosConsulta.triajes[0].motivo }}</span> </p>
								<p> <strong>Sintomatologia:</strong> <span>{{ datosConsulta.triajes[0].sintomatologia }}</span> </p>
								<p> <strong>Antecedentes:</strong> <span>{{ datosConsulta.triajes[0].antecedentes }}</span> </p>
								<p> <strong>Pruebas aplicadas</strong>: <span>{{ datosConsulta.triajes[0].pruebas }}</span> </p>
							</div>
							<div v-else>
								<p>No existen datos de triaje</p>
							</div>
						</div>

					</div>
				</div>

				<div class="card shadow mb-4">
					<div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-white"><i class="fa-regular fa-lightbulb"></i> Recomendaciones entre profesionales</h6>
						<button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalComentarios"><i class="fa-regular fa-pen-to-square"></i></button>
					</div>
					<div class="card-body">
						<ul >
							<li v-for="comentario in comentarios"> <i class="fa-regular fa-comment"></i> {{ comentario.nombre }}: {{ comentario.comment }}</li>
							<li v-if="comentarios.length==0">No hay recomendaciones previas</li>
						</ul>
					</div>
				</div>
				<div class="card shadow mb-4">
					<div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-white"><i class="fa-solid fa-paperclip"></i> Archivos adjuntos</h6>
						<button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalArchivos"><i class="fa-solid fa-upload"></i></button>
					</div>
					<div class="card-body">
						<ul >
							<span data-bs-toggle="modal" data-bs-target="#modalArchivos" style="cursor:pointer;" ><i class="fa-regular fa-file-lines"></i> Vea y adjunte archivos a la Historia Clínica</span>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-6 ">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-white"><i class="fas fa-users"></i> Persona de contacto en caso de emergencias</h6>
						<button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalEditarPariente"><i class="fa-regular fa-pen-to-square"></i></button>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="historia-info">
							<div v-if="datosConsulta.relative">
								<p class="text-capitalize"><b>Nombre:</b> {{ datosConsulta.relative.name && datosConsulta.relative.name!='null' ?
									lowerCase(datosConsulta.relative.name) : '...' }}</p>
								<p><b>Número de celular:</b> {{ datosConsulta.relative.phone && datosConsulta.relative.phone!='null' ? datosConsulta.relative.phone : '...' }}</p>
								<p><b>Parentesco:</b> {{ datosConsulta.relative.kinship && datosConsulta.relative.kinship!='null' ? datosConsulta.relative.kinship : '...' }}</p>
							</div>

							<div v-else>
								<p>No existe registro de pariente</p>
							</div>
						</div>
					</div>
				</div>
				<div class="card shadow mb-4" id="cardPerfil">
					<div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-white"><i class="fas fa-user"></i> Otros datos</h6>
					</div>
					<div class="card-body">
						<p v-if="datosPaciente.semaforo.length>0">Perfil del paciente: 
							<a class="text-capitalize" href="#!" data-bs-toggle="modal" data-bs-target="#modalVerEstados">{{queEstado(datosPaciente.semaforo[0].codigo)}}</a>
							<span v-if="datosPaciente.semaforo[0].observaciones!=''">({{ datosPaciente.semaforo[0].observaciones }})</span>
						</p>
						<p v-if="datosPaciente.semaforo.length==0">Perfil del paciente: 
							<a class="text-capitalize" href="#!" data-bs-toggle="modal" data-bs-target="#modalVerEstados">Sin asignar</a>
						</p>
						<p>Hobbies:</p>
						<div>
							<span v-for="hobbie in misHobbies" class="badge text-capitalize rounded-pill text-bg-primary mx-2 px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalVerHobbies" >{{ hobbies[hobbie] }}</span>
						</div>
						<div v-if="misHobbies.length==0">
							<span class="badge rounded-pill text-bg-secondary px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalVerHobbies" >Ninguno</span>
						</div>
						<p class="mt-2 mb-0">Visitas:</p>

						<BarChart v-if="datosConsulta.medical_evolutions" :medical_evolutions="datosConsulta.medical_evolutions" :evolucionPsiquiatria="evolucionPsiquiatria" :evolucionPsicologia="evolucionPsicologia" ></BarChart>

					</div>

				</div>
			

			</div>

			 <div class="col-md-12" > <!-- v-if="rol === 'Psiquiatra'" -->
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header bg-warning py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-white">Diagnóstico Psiquíatrico Inicial - {{
							inicialPsiquiatria.created_at ? inicialPsiquiatria.created_at.substring(0, 10) : 'Anterior de 2022' }}</h6>
					</div>
					<!-- Card Body -->
					<form class="card-body" @submit.prevent>
						<div class="historia-info">
							<div class="row row-cols-md-2">
								<!-- Inicio de Primera Columna -->
								<div class="col">
									<div class="">
										<p class="mb-0"><b>Antecedente General</b></p>
										<p class="collapse__paragraph" id="general_antecedent" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('general_antecedent')" v-if="!inicialInputPsiquiatria.general_antecedent">
											{{ inicialPsiquiatria ? inicialPsiquiatria.general_antecedent : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="anteceGeneFami" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.general_antecedent"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Principales signos y síntomas</b></p>
										<p class="collapse__paragraph" id="main_signs_symptoms" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('main_signs_symptoms')" v-if="!inicialInputPsiquiatria.main_signs_symptoms">
											{{ inicialPsiquiatria ? inicialPsiquiatria.main_signs_symptoms : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="sigSinPrin" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.main_signs_symptoms"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Problema actual</b></p>
										<p class="collapse__paragraph" id="psiquiatria_Illness" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('psiquiatria_illness')" v-if="!inicialInputPsiquiatria.illness">
											{{ inicialPsiquiatria ? inicialPsiquiatria.illness : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="efermeAct" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.illness"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>APC</b></p>
										<p class="collapse__paragraph" id="apc" :data-rol="rol === 'Psiquiatra'" @click="convertir('apc')"
											v-if="!inicialInputPsiquiatria.apc">
											{{ inicialPsiquiatria ? inicialPsiquiatria.apc : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="apcPsiqui" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.apc"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Lenguaje</b></p>
										<p class="collapse__paragraph" id="languaje" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('languaje')" v-if="!inicialInputPsiquiatria.languaje">
											{{ inicialPsiquiatria ? inicialPsiquiatria.languaje : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="lenguaje" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.languaje"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Pensamiento</b></p>
										<p class="collapse__paragraph" id="thought" :data-rol="rol === 'Psiquiatra'" @click="convertir('thought')"
											v-if="!inicialInputPsiquiatria.thought">
											{{ inicialPsiquiatria ? inicialPsiquiatria.thought : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="pensamiento" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.thought"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Afecto</b></p>
										<p class="collapse__paragraph" id="affect" :data-rol="rol === 'Psiquiatra'" @click="convertir('affect')"
											v-if="!inicialInputPsiquiatria.affect">
											{{ inicialPsiquiatria ? inicialPsiquiatria.affect : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="afecto" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.affect"></textarea>
										</div>
									</div>
								</div>
								<!-- Fin de Primera Columna -->
								<!-- Inicio de Segunda columna -->
								<div class="col">
									<div>
										<p class="mb-0"><b>Percepción</b></p>
										<p class="collapse__paragraph" id="percetion" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('percetion')" v-if="!inicialInputPsiquiatria.percetion">
											{{ inicialPsiquiatria ? inicialPsiquiatria.percetion : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="percepcion" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.percetion"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Función superior</b></p>
										<p class="collapse__paragraph" id="superior_function" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('superior_function')" v-if="!inicialInputPsiquiatria.superior_function">
											{{ inicialPsiquiatria ? inicialPsiquiatria.superior_function : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="funciones" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.superior_function"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Abstracción</b></p>
										<p class="collapse__paragraph" id="abstraction" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('abstraction')" v-if="!inicialInputPsiquiatria.abstraction">
											{{ inicialPsiquiatria ? inicialPsiquiatria.abstraction : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="abstraccion" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.abstraction"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Conciencia</b></p>
										<p class="collapse__paragraph" id="conscience" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('conscience')" v-if="!inicialInputPsiquiatria.conscience">
											{{ inicialPsiquiatria ? inicialPsiquiatria.conscience : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="conciencia" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.conscience"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Insight</b></p>
										<p class="collapse__paragraph" id="insight" :data-rol="rol === 'Psiquiatra'" @click="convertir('insight')"
											v-if="!inicialInputPsiquiatria.insight">
											{{ inicialPsiquiatria ? inicialPsiquiatria.insight : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="insight" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.insight"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Problemas Diagnóstico</b></p>
										<p class="collapse__paragraph" id="diagnostic_problems" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('diagnostic_problems')" v-if="!inicialInputPsiquiatria.diagnostic_problems">
											{{ inicialPsiquiatria ? inicialPsiquiatria.diagnostic_problems : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="problemsDiag" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.diagnostic_problems"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Diagnóstico</b></p>
										<div class="form-group position-relative diagnostico-input" style="min-height: 100px;">
											<div class="position-relative">
												<input type="text" class="form-control" autocomplete="off" name="diagnostico" id="diagnostico"
													v-model="searchCie" @keyup="getDiagnostico">
												<button class="btn btn-warning position-absolute update-diagnostic" @click="updateDiag">
													<i class="fas fa-save" title="Guardar cambios"></i>
												</button>
												<!-- v-model="inicialPsiquiatria.diagnostic" -->
												<div class="cie-content rounded overflow-auto">
													<div v-for="(cie, index) in dataCies" :key="index">
														<span class="w-100 px-2 py-2 cie--hover d-inline-block pointer cie-item"
															:class="{ 'cie-danger': inicialPsiquiatria.diagnostic.find(el => el == cie.id) }"
															:data-id="cie.id" @click="addCie">
															{{ cie.id }} - {{ cie.code }} - {{ cie.description }}
														</span>
													</div>
												</div>
											</div>
											<div class="d-flex flex-gap flex-wrap mt-3 overflow-auto">
												<div v-if="cieAdd" v-for="(cieAgregado, index) in datosConsulta.cies" :key="`cie${index}`"
													class="bg-warning rounded text-light p-2">
													{{ typeof cieAgregado == 'object' ? `${cieAgregado.id} - ${cieAgregado.code} -
																								${cieAgregado.description}` : cieAgregado }}
													<span :data-cie="index" class="cie-item ml-2 pointer" @click="deleteCie"><i
															class="fas fa-times"></i></span>
												</div>
											</div>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Plan</b></p>
										<p class="collapse__paragraph" id="psiquiatria_plan" :data-rol="rol === 'Psiquiatra'"
											@click="convertir('psiquiatria_plan')" v-if="!inicialInputPsiquiatria.plan">
											{{ inicialPsiquiatria ? inicialPsiquiatria.plan : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="plan" id="" cols="10" rows="3" class="form-control"
												v-model="inicialPsiquiatria.plan"></textarea>
										</div>
									</div>
								</div>
								<!-- Fin de Segunda columna -->
							</div>
							
							
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="row mt-3 ">
			<div class="col-md-12"> <!-- v-if="rol === 'Psicólogo'" -->
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header bg-warning py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-white">Diagnostico Psicológico Inicial - {{
							initialPsychological.created_at ? initialPsychological.created_at.substring(0, 10) : 'Anterior de 2022' }}
						</h6>
					</div>
					<!-- Card Body -->
					<form class="card-body" @submit.prevent>
						<div class="historia-info">
							<div class="row row-cols-md-2">
								<div class="col">
									<div>
										<p class="mb-0"><b>Problema actual</b></p>
										<p class="collapse__paragraph" id="Psicologia_illness" :data-rol="rol === 'Psicólogo'"
											@click="convertir('Psicologia_illness')" v-if="!inicialInputPsychological.illness">
											{{ initialPsychological.illness ? initialPsychological.illness : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="" id="" cols="10" rows="3" class="form-control"
												v-model="initialPsychological.illness"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Antecedentes</b></p>
										<p class="collapse__paragraph" id="antecedent" :data-rol="rol === 'Psicólogo'"
											@click="convertir('antecedent')" v-if="!inicialInputPsychological.antecedent">
											{{ initialPsychological.antecedent ? initialPsychological.antecedent : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="" id="" cols="10" rows="3" class="form-control"
												v-model="initialPsychological.antecedent"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Dinámica</b></p>
										<p class="collapse__paragraph" id="dynamic" :data-rol="rol === 'Psicólogo'" @click="convertir('dynamic')"
											v-if="!inicialInputPsychological.dynamic">
											{{ initialPsychological.dynamic ? initialPsychological.dynamic : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="" id="" cols="10" rows="3" class="form-control"
												v-model="initialPsychological.dynamic"></textarea>
										</div>
									</div>
								</div>
								<div class="col">
									<div>
										<p class="mb-0"><b>Actitud actual</b></p>
										<p class="collapse__paragraph" id="attitude" :data-rol="rol === 'Psicólogo'"
											@click="convertir('attitude')" v-if="!inicialInputPsychological.attitude">
											{{ initialPsychological.attitude ? initialPsychological.attitude : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="" id="" cols="10" rows="3" class="form-control"
												v-model="initialPsychological.attitude"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>DX</b></p>
										<p class="collapse__paragraph" id="dx" :data-rol="rol === 'Psicólogo'" @click="convertir('dx')"
											v-if="!inicialInputPsychological.dx">
											{{ initialPsychological.dx ? initialPsychological.dx : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="" id="" cols="10" rows="3" class="form-control"
												v-model="initialPsychological.dx"></textarea>
										</div>
									</div>
									<div>
										<p class="mb-0"><b>Plan</b></p>
										<p class="collapse__paragraph" id="Psicologia_plan" :data-rol="rol === 'Psicólogo'"
											@click="convertir('Psicologia_plan')" v-if="!inicialInputPsychological.plan">
											{{ initialPsychological.plan ? initialPsychological.plan : '...' }}
										</p>
										<div class="collpase__textarea" v-else>
											<textarea name="" id="" cols="10" rows="3" class="form-control"
												v-model="initialPsychological.plan"></textarea>
										</div>
									</div>
								</div>
							</div>
							
							
						</div>
					</form>
				</div>
			</div>
		</div>

		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="evoluciones-tab" data-bs-toggle="tab" data-bs-target="#evoluciones-tab-pane" type="button" role="tab" aria-controls="evoluciones-tab-pane" aria-selected="true"><i class="fas fa-tv"></i> Evoluciones del paciente</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="linea-tab" data-bs-toggle="tab" data-bs-target="#linea-tab-pane" type="button" role="tab" aria-controls="linea-tab-pane" aria-selected="false"><i class="fas fa-sort-amount-up-alt"></i> Línea de vida</button>
			</li>
		</ul>

		<div class="tab-content mb-5" id="myTabContent">
			<div class="tab-pane fade show active p-3 bg-light-subtle border border-top-0" id="evoluciones-tab-pane" role="tabpanel" aria-labelledby="evoluciones-tab" tabindex="0">
				<!-- Titulo -->
				<div class="d-flex justify-content-between">
					<!-- <h4>Evoluciones del paciente</h4> -->
					<!-- <button
					data-toggle="modal"
					data-target="#evolutionModal"
					@click="evoluciones"
					class="bg-warning px-4 py-2 rounded border-0 text-light btn--iteration"
					v-if="consultaHoy"
					>
					Nueva evolución
					</button> -->
				</div>

				<!-- Card -->
				<div class="row mb-5">
					<div class="col-md-4 mb-3" v-for="(evolution, index) in datosConsulta.medical_evolutions" :key="index">
						<div class="card mb-4 tarjeta" @mouseover="colorear(index)" @mouseleave="descolorear(index)" :class="dondeEsta(evolution.type)">
							<!-- Card Header - Dropdown -->
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" @click="mostrarCard(index)" data-bs-toggle="modal" data-bs-target="#modalVerDetalle" >
								<h6 class="m-0 font-weight-bold text-white text-capitalize">{{ fechaLectura(evolution.date) }} <small>(#{{ evolution.id }})</small></h6>
							</div>
							<!-- Card Body -->
							<div class="card-body">
								<div class="card-evolution">
									<div class="historia-info">
										<p><b>Clase:</b> {{ dondeEsta2(evolution.type) }}</p>
										<p><b>Profesional:</b> {{ evolution.professional ? evolution.professional.name : 'Sin asignar' }} </p>
										<p><b>Comentario: </b> {{ evolution ? maxStringCharacter(evolution.content, 50) : '...' }} </p>
									</div>
									<div class="card-evolution__image d-none">
										<img :src="`/storage/${evolution.professional ? evolution.professional.signing : null}`"
											class="card-evolution-image"
											:alt="evolution.professional ? evolution.professional.signing : null ? 'Firma del doctor' : 'Sin firma'">
									</div>
								</div>
								<div class="d-flex flex-gap">
									<button @click="updateModal(evolution)" data-bs-toggle="modal" data-bs-target="#updatedModal" class="btn btn-outline-secondary" v-if="evolution.professional_id == dataUser.id && 
                      calcularDias(evolution.date)<=2 || evolution.auth == 1">
										<i class="fas fa-edit"></i> Redactar evolución
									</button>
									<button @click="editEvolution(evolution)" class="btn btn-success d-none"
										v-if="evolution.professional_id == dataUser.id" data-bs-toggle="modal" data-bs-target="#editModal">
										<i class="fas fa-edit"></i>
									</button>
									<!-- <div v-if="evolution.professional_id == dataUser.id &&
										evolution.date === getDateNow()" class="btn-group">
										<button v-if="autoSaveInfo != null" @click="refreshInfo(evolution.id)"
											class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuClickableInside"
											data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
											<i class="fas fa-save"></i>
										</button>
										<ul class="dropdown-menu px-2 py-2" aria-labelledby="dropdownMenuClickableInside">
											<span>{{ autoSaveInfo }}</span>
										</ul>
									</div> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade py-5 px-3 bg-light-subtle border border-top-0" id="linea-tab-pane" role="tabpanel" aria-labelledby="linea-tab" tabindex="0">
				
				<button class="btn btn-outline-success" data-bs-target="#nuevoAcontecimiento" data-bs-toggle="modal"><i class="far fa-comment-alt"></i> Agregar nuevo acontecimiento</button>
				<lineaTiempo :id="$route.params.idPaciente" ></lineaTiempo>
				
			</div>
			
		</div>

		

		<!-- Modal de examenes -->
		<div class="modal fade" id="examenModal" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-warning text-white">
						<h5 class="modal-title" id="infoModalLabel">Exámenes</h5>
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i
								class="fas fa-times"></i></button>
					</div>

					<keep-alive>
						<component v-if="this.datosExamPaciente.id" :is="component" @keepComponentExam="keepExamFunction"
							:dataPatient="datosExamPaciente" :dataExam="this.datosExamenes" :dataExamBasic="datosConsulta.examenes_basicos" :dataExamPersonalized="datosConsulta.examenes_personalizados" :profesionales="profesionalesTodos" />
					</keep-alive>

				</div>
			</div>
		</div>


		<!-- Modal de recetas -->
		<div class="modal fade" id="recetasModal" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title" id="infoModalLabel">Ver recetas</h5>
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i
								class="fas fa-times"></i></button>
					</div>

					<div class="modal-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Fecha</th>
									<th>Ver Receta</th>
								</tr>
							</thead>
							<tbody>
								<tr v-if="datosConsulta" v-for="(prescription, index) in datosConsulta.prescriptions" :key="index">
									<td>{{ index + 1 }}</td>
									<td>{{ prescription ? prescription.attention_date : '...' }}</td>
									<td>
										<a v-if="prescription" class="btn btn-success" :href="`/api/pdf/${prescription.id}`"
											target="_blank">Ver PDF</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal de actualizar -->
		<updated-modal :datosModal="dataModal"></updated-modal>

		<!-- Modal editar -->
		<edit-modal :datosModal="dataModal"></edit-modal>
		<modalVerDetalle :miniRespuesta="miniRespuesta"></modalVerDetalle>
		<modal-ver-triajes-viejos :triajes = "datosConsulta.triajes"></modal-ver-triajes-viejos>
		<ModalEditarPariente v-if="datosConsulta" :relative="datosConsulta.relative" @updatePariente="updatePariente"></ModalEditarPariente>
		<ModalEditarPaciente v-if="dato1" :dataPatient="dato1" ></ModalEditarPaciente>
		<ModalVerEstados :dataPatient="datosPaciente" :estados="estados"></ModalVerEstados>
		<ModalVerHobbies :hobbies="hobbies" :id="datosConsulta.id" :misHobbies="misHobbies" ></ModalVerHobbies>
		<ModalComentarios :comentarios="comentarios" :idProfesional="dataUser.id" @refrescarComentarios="updateComentarios" ></ModalComentarios>
		<ModalProximaCita :profesional="dataUser" :paciente="datosPaciente"></ModalProximaCita>
		<ModalArchivos :idPaciente="datosConsulta.id" :idProfesional="dataUser.id" ></ModalArchivos>
		<ModalNuevoAcontecimiento :idPaciente="datosConsulta.id" :idProfesional="dataUser.id"></ModalNuevoAcontecimiento>
	</div>
</template>

<script>
import editModal from './editEvolution.vue'
import updatedModal from './updatedEvolutionModal.vue';
import ExamResult from './ExamResult.vue';
import ExamTable from './ExamTable.vue';
import { dateNow } from '../../../../helpers/Time.js'
import moment from 'moment';
import modalVerDetalle from './ModalVerDetalle.vue';
import ModalComentarios from './ModalComentarios.vue';
import ModalVerTriajesViejos from './../../../recepcionista/components/pacientes/ModalVerTriajesViejos.vue';
import ModalEditarPaciente from './../../../recepcionista/components/pacientes/ModalEditPatients.vue'
import ModalEditarPariente from './ModalEditarPariente.vue'
import ModalVerEstados from './../../../recepcionista/components/pacientes/ModalVerEstados.vue'
import ModalVerHobbies from './../../../recepcionista/components/pacientes/reportes/ModalVerHobbies.vue'
import ModalProximaCita from './ModalProximaCita.vue'
import ModalArchivos from './ModalArchivos.vue'
import BarChart  from './grafico/barras'
import ModalNuevoAcontecimiento from './ModalNuevoAcontecimiento.vue'
import lineaTiempo from './grafico/lineaTiempo.vue'

export default {
	name: 'evolucionPaciente',

	components: { updatedModal, ExamResult, ExamTable, editModal, modalVerDetalle, ModalVerTriajesViejos, ModalEditarPariente, ModalVerEstados, ModalEditarPaciente, ModalVerHobbies, BarChart, ModalComentarios, ModalProximaCita, ModalArchivos, ModalNuevoAcontecimiento, lineaTiempo },

	data() {
		return {
			autoSaveInfo: '', profesionalesTodos:[],
			datosConsulta: { triajes:[], examenes_basicos:[], examenes_personalizados:{burns:[], gads:[], scrs:[], zung_anxieties :[], zung_depressions :[]} },
			evolucionPsiquiatria: [1,2,3,4,5,6,16,17], //Ver tabla de precios, son los IDs
			evolucionPsicologia: [7,8,9,10,11,12,18,22,27], //Ver tabla de precios, son los IDs
			cardUpdate: '',         // Va el el nombre del card | Psiquiatra | Psicologia | perfil | etc
			dobleClick: false,relative: [],
			switch: 0,
			inputActive: '',
			rol: '',
			consultaHoy: false,
			dataCies: null,
			searchCie: '',
			cieAdd: [], indexGlobal: -1, miniRespuesta: { nombre: '', contenido: '', firma: '' }, dato1:{}, comentarios:[],

			component: "ExamTable",
			datosExamenes: [],
			datosExamPaciente: {
				name: '',
				id: '',
				tipoExam: 'SCL90R'
			},
			estados:[
				{id: 1, valor: 'Neutro', detalle: 'No tiene ningún registro de actitud'},
				{id: 2, valor: 'cumplidor', detalle: 'es un paciente exclente, comprometido'},
				{id: 3, valor: 'promotor', detalle: 'promueve actividades entre sus compañeros o la empresa'},
				{id: 4, valor: 'wow', detalle: 'es involucrado en actividades, participativo'},
				{id: 5, valor: 'reprogramador', detalle: 'suele aplazarcitas y actividades'},
				{id: 6, valor: 'exigente', detalle: 'un paciente/cliente que siempre pide un trato especial'},
				{id: 7, valor: 'deudor', detalle: 'paciente con deudas'},
				{id: 8, valor: 'insatisfecho', detalle: 'suele quejarse de los servicios'},
				{id: 9, valor: 'peligroso', detalle: 'paciente con amenazas o actos de violencia.'},
			], datosPaciente:{ semaforo:[]},

			inicialPsiquiatria: {
				id: '',
				main_signs_symptoms: '...',
				general_antecedent: '...',
				illness: '...',
				apc: '...',
				languaje: '...',
				thought: '...',
				affect: '...',
				percetion: '...',
				superior_function: '...',
				abstraction: '...',
				conscience: '...',
				insight: '...',

				diagnostic_problems: '...',
				diagnostic: '...',
				plan: '...',

				professional_id: '',
				patient_id: '',

				created_at: ''
			},

			initialPsychological: {
				id: '',
				illness: '...', // Enfermedad
				antecedent: '...',
				dynamic: '...',
				attitude: '...',
				dx: '...',
				plan: '...',
				professional_id: '',
				patient_id: '',

				created_at: ''
			},

			inicialInputPsiquiatria: {
				main_signs_symptoms: false,
				general_antecedent: false,
				illness: false,
				apc: false,
				languaje: false,
				thought: false,
				affect: false,
				percetion: false,
				superior_function: false,
				abstraction: false,
				conscience: false,
				insight: false,
				diagnostic_problems: false,
				diagnostic: false,
				plan: false,
			},

			inicialInputPsychological: {
				illness: false,
				antecedent: false,
				dynamic: false,
				attitude: false,
				dx: false,
				plan: false,
			},

			dataModal: {},
			hobbies:['pintura','dibujo', 'fotografía', 'tejido', 'costura', 'joyería', 'senderismo', 'acampar', 'jardinería', 'pesca', 'ciclismo', 'deportes', 'fútbol', 'basket', 'tenis', 'ajedrez', 'juegos de mesa', 'billar', 'música', 'tocar un instrumento', 'canto', 'composición musical', 'producción musical', 'gastronomía', 'cocina', 'recetas', 'horneado', 'postres', 'manualidades', 'origami', 'modelodo en arcilla', 'creación', 'natación', 'surf', 'kayac', 'buceo', 'esquí', 'tecnología', 'programación', 'robótica', 'computación', 'edición de videos', 'diseño gráfico', 'coleccionismo', 'monedas', 'vinilos', 'baile', 'danzas', 'escritura', 'periodismo', 'poesía', 'libros', 'lectura', 'cuentos', 'idiomas', 'viajes', 'exploración de lugares', 'fitnes', 'gym', 'yoga', 'pilates', 'entrenamiento', 'meditación', 'voluntariado', 'mascotas', 'animalista', 'astronomía', 'jardinería', 'plantas', 'huertos', 'paisajes', 'cine', 'series', 'novelas'], misHobbies:[],

			// Datos para agregar una nueva evolución
			evolution: {
				content: '',
				auth: 0
			}
		}
	},

	props: {
		dataUser: Object,
		datosModal: Object,
		dataPatient: {},
		dataExam: Array
	},

	methods: {
		refreshInfo(id) {
			this.autoSaveInfo = localStorage.getItem(id)
		},
		toDischarge() {
			this.$swal({
				title: '¿Está seguro de dar de alta a este paciente?',
				showDenyButton: true,
				confirmButtonText: 'Si',
				denyButtonText: 'No',
			}).then((result) => {
				if (result.isConfirmed) {
					this.axios.get(`/api/discharge/${this.$route.params.idPaciente}/${this.$attrs.idUser}`)
						.then((res) => {
							this.$swal(res.data.msg)
						});
				}
			})
		},
		editEvolution(evolution) {
			this.dataModal = evolution
		},
		updatePariente(pariente){
			this.datosConsulta.relative.name = pariente.nombre
			this.datosConsulta.relative.phone = pariente.celular
			this.datosConsulta.relative.kinship = pariente.parentesco
		},
		async getHistories() {
			await this.axios.get(`/api/patientEvolution/${this.$route.params.idPaciente}`)
				.then(res => {
					this.datosConsulta = res.data;
					this.misHobbies = JSON.parse(this.datosConsulta.hobbies)
					//console.log(this.datosConsulta)
					this.datosConsulta.medical_evolutions = this.datosConsulta.medical_evolutions.sort(function (a, b) {
						if (a.date < b.date) {
							return -1;
						}
						if (a.date > b.date) {
							return 1;
						}

						return 0;
					})

					this.datosExamPaciente.name = this.datosConsulta.name;
					this.datosExamPaciente.id = this.datosConsulta.id;


					if (res.data.initial_psychiatric_history) {
						this.inicialPsiquiatria = res.data.initial_psychiatric_history
						this.inicialPsiquiatria.diagnostic = [];
						res.data.cies.forEach(el => {
							this.inicialPsiquiatria.diagnostic.push(el.id.toString())
						})
					} else {
						this.inicialPsiquiatria;
					}

					res.data.initial_psychological_history
						? this.initialPsychological = res.data.initial_psychological_history
						: this.initialPsychological;

					this.datosConsulta.medical_evolutions = this.datosConsulta.medical_evolutions.reverse()

					this.evolution.patient_id = this.datosConsulta.id;
				})
				.catch(err => {
					console.error(err)
				})
			await this.axios.get(`/api/datosPacienteSemaforo/${this.$route.params.idPaciente}`)
			.then(res =>{
				this.datosPaciente={
					id: this.datosConsulta.id,
					name: this.datosConsulta.name,
					semaforo: res.data
				}
			})
			this.axios.get('/api/profesional')
			.then(res=> this.profesionalesTodos= res.data)

			this.axios.get('/api/listRecomendation/'+this.$route.params.idPaciente)
			.then(res=> this.comentarios= res.data)
			
		},

		async updatedConsult() {
			let data = Object;
			if (this.dataUser.profession === 'Psiquiatra') {
				data = this.inicialPsiquiatria
			} else {
				data = this.initialPsychological
			}

			let url = this.dataUser.profession === 'Psiquiatra' ? 'initialPsychiatric' : 'initialPsychological'
			let idHistoria = this.dataUser.profession === 'Psiquiatra' ? this.datosConsulta.initial_psychiatric_history.id : this.datosConsulta.initial_psychological_history.id

			await axios.put(`/api/${url}/${idHistoria}`, data)
				.then(res => {
					// console.log(res.data)
				})
				.catch(err => {
					console.error(err)
				})

			this.switch = 0
			this.inputSwitchActive(this.inputActive, false)
			this.inputActive = ""
		},
		updateComentarios(){
			this.axios.get('/api/listRecomendation/'+this.$route.params.idPaciente)
			.then(res=> this.comentarios= res.data)
		},

		// async addEvolution () {
		//   await this.axios.post('/api/evolution', this.evolution)
		//   .then(res => {
		//     this.getHistories()
		//   })
		//   .catch(err => {
		//     console.error(err)
		//   })
		// },

		async getDiagnostico() {
			await this.axios.get(`/api/cies/${this.searchCie === '' ? 'a' : this.searchCie}`)
				.then(res => {
					this.dataCies = res.data;
				})

				.catch(err => {
					console.error(err)
				})
		},

		keepExamFunction(info) {
			console.log(info)
			this.component = info.component || info,
				this.datosExamenes = info.data
		},

		updateDiag() {
			this.updatedConsult()
		},

		updateModal(data) {
			this.dataModal = data
		},

		collapse() {
			if (event.target.closest('.ques').matches(".active")) {
				event.target.closest('.ques').classList.remove("active")
				event.target.closest('.ques').removeAttribute("style")
				event.target.closest('.ques').querySelector(".collapse").removeAttribute("style");
			} else {
				let heightQues = event.target.closest('.ques').scrollHeight;

				document.querySelectorAll(".ques.active").forEach(el => {
					el.classList.remove("active")
					el.removeAttribute("style")
					el.querySelector(".collapse").removeAttribute("style");
				})

				event.target.closest('.ques').classList.toggle("active")
				event.target.closest('.ques').style.height = `${heightQues}px`
				event.target.closest('.ques').querySelector(".collapse").style.background = "rgba(231, 231, 231, 0.788)";
			}
		},

		collapseActive(event) {
			let heightQues = event.target.closest('.ques').scrollHeight;
			event.target.closest('.ques').style.height = `${heightQues + 80}px`
		},

		clickOutside() {
			if (this.inputActive) {
				if (!event.target.matches(".collpase__textarea, .collpase__textarea *") && document.querySelector(".collpase__textarea")) {
					if (this.switch !== 0) {
						this.updatedConsult()
					} else {
						this.switch += 1
					}
				}
			}

			if (this.dobleClick !== event.target.id) {
				this.dobleClick = event.target.id
				return
			}
		},

		inputSwitchActive(prop, value) {
			// Psicplogia
			switch (prop) {
				case 'Psicologia_illness':
					this.inicialInputPsychological.illness = value; break;
				case 'antecedent':
					this.inicialInputPsychological.antecedent = value; break;
				case 'dynamic':
					this.inicialInputPsychological.dynamic = value; break;
				case 'attitude':
					this.inicialInputPsychological.attitude = value; break;
				case 'dx':
					this.inicialInputPsychological.dx = value; break;
				case 'Psicologia_plan':
					this.inicialInputPsychological.plan = value; break;
			}

			// Psiquiatria
			switch (prop) {
				case 'main_signs_symptoms': this.inicialInputPsiquiatria.main_signs_symptoms = value; break;
				case 'general_antecedent':
					this.inicialInputPsiquiatria.general_antecedent = value; break;
				case 'psiquiatria_illness':
					this.inicialInputPsiquiatria.illness = value; break;
				case 'apc':
					this.inicialInputPsiquiatria.apc = value; break;
				case 'languaje':
					this.inicialInputPsiquiatria.languaje = value; break;
				case 'thought':
					this.inicialInputPsiquiatria.thought = value; break;
				case 'affect':
					this.inicialInputPsiquiatria.affect = value; break;
				case 'percetion':
					this.inicialInputPsiquiatria.percetion = value; break;
				case 'superior_function':
					this.inicialInputPsiquiatria.superior_function = value; break;
				case 'abstraction':
					this.inicialInputPsiquiatria.abstraction = value; break;
				case 'conscience':
					this.inicialInputPsiquiatria.conscience = value; break;
				case 'insight':
					this.inicialInputPsiquiatria.insight = value; break;
				case 'diagnostic_problems':
					this.inicialInputPsiquiatria.diagnostic_problems = value; break;
				case 'diagnostic':
					this.inicialInputPsiquiatria.diagnostic = value; break;
				case 'psiquiatria_plan':
					this.inicialInputPsiquiatria.plan = true; break;
			}
		},

		convertir(prop) {
			console.log(event)
			if (event.target.dataset.rol == "true") {
				if (this.dobleClick == false) {
					this.dobleClick = "hola";
					return;
				}

				if (this.dobleClick === event.target.id) {
					this.inputActive = prop;
					this.inputSwitchActive(prop, true);
					//this.collapseActive(event);

					this.dobleClick = false;
				} else {
					this.dobleClick = false;
				}
			} else {
				//alert('No tienes acceso para editar a este paciente')
				this.$swal.fire({title: 'No tiene acceso para editar este bloque', icon:'error'})
			}
		},

		lowerCase(text = '') {
			return text.toLowerCase();
		},

		evoluciones() {
			this.evolution.type = this.dataUser.profession === 'Psiquiatra' ? 1 : 2,
				this.evolution.date = dateNow(),
				this.evolution.schedule = this.getTiempo(),
				this.evolution.professional_id = this.dataUser.id
		},

		addCie() {
			let verify = true;

			if (this.datosConsulta.cies.length === 0) {
				this.inicialPsiquiatria.diagnostic.push(event.target.dataset.id)
				this.datosConsulta.cies.push(event.target.textContent.trim());
				return;
			}

			this.datosConsulta.cies.find(el => {
				if (typeof el == 'object') {
					if (`${el.id} - ${el.code} - ${el.description}` === event.target.textContent.trim()) {
						return verify = false
					}
				} else {
					if (el === event.target.textContent.trim()) {
						return verify = false
					}
				}
			})

			if (verify === true) {
				this.inicialPsiquiatria.diagnostic.push(event.target.dataset.id)
				this.datosConsulta.cies.push(event.target.textContent.trim());
			}
		},

		deleteCie() {
			this.datosConsulta.cies.splice(parseInt(event.target.closest('.cie-item').dataset.cie), 1)
			this.inicialPsiquiatria.diagnostic.splice(parseInt(event.target.closest('.cie-item').dataset.cie), 1)
		},

		getTiempo() {
			return `${new Date().getHours().toString().length == 1 ? '0' + new Date().getHours() : new Date().getHours()}:${new Date().getMinutes().toString().length == 1 ? '0' + new Date().getMinutes() : new Date().getMinutes()}:${new Date().getSeconds().toString().length == 1 ? '0' + new Date().getSeconds() : new Date().getSeconds()}`
		},

		calculateAge(age) {
			return moment().diff(age, 'years');
			//return age ? new Date().getFullYear() - parseInt(age.substring(0,4)) : '...';
		},

		getDateNow() {
			return dateNow()
		},
		calcularDias(fecha){
			let hoy = moment()
			return hoy.diff(fecha, 'days')
		},
		maxStringCharacter(character, num) {
			character === null ? character = '...' : character
			if (character.length > num) {
				return character.substring(0, num) + '...';
			} else {
				return character;
			}
		},
		fechaLatam(fecha) {
			return moment(fecha).format('DD/MM/YYYY');
		},
		fechaLectura(fecha) {
			moment.locale('es')
			return moment(fecha).format('dddd, DD [de] MMMM YYYY');
		},
		colorear(index) {
			let cabecera = document.querySelectorAll('.tarjeta .card-header')[index]
			cabecera.closest('.card').classList.add('shadow');
			//cabecera.classList.remove('bg-secondary');
			//cabecera.classList.add('bg-success');
		},
		descolorear(index) {
			let cabecera = document.querySelectorAll('.tarjeta .card-header')[index]
			cabecera.closest('.card').classList.remove('shadow');
			//cabecera.classList.remove('bg-success');
			//cabecera.classList.add('bg-secondary');
		},
		mostrarCard(index) {
			this.indexGlobal = index;
			if (this.datosConsulta.medical_evolutions[index].professional) {
				this.miniRespuesta.nombre = this.datosConsulta.medical_evolutions[index].professional.name;
				this.miniRespuesta.firma = this.datosConsulta.medical_evolutions[index].professional.signing;
				this.miniRespuesta.foto = this.datosConsulta.medical_evolutions[index].professional.photo;
				if (this.miniRespuesta.firma == '-') {
					this.miniRespuesta.firma = '';
				}
			} else {
				this.miniRespuesta.nombre = 'Sin asignar';
				this.miniRespuesta.firma = '';
				this.miniRespuesta.foto = '';
			}
			this.miniRespuesta.contenido = this.datosConsulta.medical_evolutions[index].content;
		},
		contarExamenes(){
			if(this.datosConsulta!== undefined){
				
				return this.datosConsulta.examenes_basicos.length + this.datosConsulta.examenes_personalizados.burns.length + this.datosConsulta.examenes_personalizados.gads.length + this.datosConsulta.examenes_personalizados.scrs.length + this.datosConsulta.examenes_personalizados.zung_anxieties.length + this.datosConsulta.examenes_personalizados.zung_depressions.length 
			}
			//datosConsulta.examenes[0].length + datosConsulta.examenes[1]['burns'].length + datosConsulta.examenes[1]['gads'].length + datosConsulta.examenes[1]['scrs'].length + datosConsulta.examenes[1]['zung_anxieties'].length + datosConsulta.examenes[1]['zung_depressions'].length
		},
		queEstado(dato){
			if(dato!== undefined) return this.estados.find(x=> x.id == dato).valor
			else return '';
		},
		async datos1Paciente(){
			this.axios.get('/api/patientById/'+this.$route.params.idPaciente)
			.then(res => {
				this.dato1 = res.data[0];
				$("#patientModal" ).modal('show')
			}) 
		},
		dondeEsta(tips){
			let valor = null
			if (valor = this.evolucionPsiquiatria.indexOf(tips)>-1) return 'evolucionPsiquiatria'
			else if (valor = this.evolucionPsicologia.indexOf(tips)>-1) return 'evolucionPsicologia'
			else return 'evoOtro'
		},
		dondeEsta2(tips){
			let valor = null
			if (valor = this.evolucionPsiquiatria.indexOf(tips)>-1) return 'Psiquiatría'
			else if (valor = this.evolucionPsicologia.indexOf(tips)>-1) return 'Psicología'
			else return 'Otro sin especificar'
		},
		/* cargarLineas(){
			this.axios('/api/cargarLineas/'+this.datosPaciente.id)
			.then(res=> { this.lineas=res.data; this.$emit('ordenarLineas')})
		} */
	},

	computed: {
		updatedValues() {
			return this.dataUser.profession === 'Psiquiatra' ? this.rol = 'Psiquiatra' : this.rol = 'Psicólogo'
		},
		
		evolutionToday() {
			if (this.datosConsulta.medical_evolutions) {
				if (this.datosConsulta.medical_evolutions.length === 0) {
					return this.consultaHoy = true
				}

				this.datosConsulta.medical_evolutions.forEach(el => {
					if (el.date === dateNow()) {
						return this.consultaHoy = false
					} else {
						this.consultaHoy = true
					}
				})
			}
		},
	
	},

	created() {
		this.getIdPatient;
	},

	mounted() {
		this.getHistories();
		//this.hobbies.sort();
	},

	updated() {
		this.updatedValues
		this.evolutionToday
	}
}
</script>

<style scoped>
.evolucionPsiquiatria .card-header{ background: #e74a3b }
.evolucionPsicologia .card-header{ background: #4e73df }
.evoOtro .card-header{ background: rgb(88, 88, 107)}
.tarjeta .card-header:hover {
	cursor: pointer;
}

h4 {
	font-weight: 500;
}
#cardPerfil .badge{cursor:pointer;}
.btn--edit {
	width: 95%;
	max-width: 180px;
	display: block;
	background: #6236FF;
	border: none;
	border-radius: 5px;
	padding: 5px 0;
	outline: none;
	color: #fff;
	border: 5px;
}

.btn--iteration:active {
	transform: scale(.95);
}

.ques {
	height: 44px;
	overflow: hidden;
	transition: height .5s ease;
}

.collapse {
	padding: 10px;
	background: rgba(235, 235, 235, 0.37);
	cursor: pointer;
}

.collapse__paragraph {
	padding: 10px;
	cursor: pointer;
	user-select: none;
}

.collpase__textarea {
	padding: 10px;
	height: 80%;
}

.collapse:hover {
	background: rgba(231, 231, 231, 0.788);
}

.collpase__textarea textarea {
	min-height: 120px !important;
	max-height: 100% !important;
}

/*.card-evolution {
	 display: grid;
    grid-template-columns: 70% 1fr;
    place-items: center;
    grid-gap: 25px; 
}*/

.card-evolution__image {
	width: 100%;
}

.historia-info {
	width: 100%;

}

.card-evolution-image {
	width: 100%;
}

.flex-gap {
	gap: 25px;
}

.cie-content {
	width: 100%;
	background-color: #fff;
	border: .3px solid #22222260;
	visibility: hidden;
	position: absolute;
	z-index: 10000;
	transition: visibility 1s normal 3s;
}

#diagnostico:focus~.cie-content {
	visibility: visible;
	/* display: block !important; */
}

.cie-item:active {
	visibility: visible;
	opacity: 0;
	/* display: block !important; */
}

.flex-gap {
	gap: 15px;
}

.pointer {
	cursor: pointer;
}

.cie--hover:hover:not(.cie-danger) {
	background: rgb(236, 236, 236);
}

.cie-danger {
	background: rgb(255, 207, 207);
	cursor: no-drop;
}

.diagnostico-input {
	overflow: auto;
	height: 100%;
	min-height: 320px;
	padding-bottom: 50px;
	/* min-height: 150px; */
}

.update-diagnostic {
	top: 0;
	right: 0;
	bottom: 0;
}

@media screen and (max-width: 750px) {
	.card-evolution {
		grid-template-columns: 1fr;
	}

	.card-evolution__image {
		display: none;
	}
}</style>

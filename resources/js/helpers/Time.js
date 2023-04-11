export function dateNow () {
  let mes = `0${new Date().getMonth() + 1}`.substr(-2),
    dia = `0${new Date().getDate()}`.substr(-2),
    año = new Date().getFullYear();
  return `${año}-${mes}-${dia}`
}

export function operationDate (day = 0, operation) {
  let hoy = new Date();
  let time = 1000 * 60 * 60 * 24 * day;
  let result = operation ? hoy.getTime() + time : hoy.getTime() - time
  return new Date(result);
}

export function formatDate (date) {
  let mes = `0${date.getMonth() + 1}`.substr(-2),
    dia = `0${date.getDate()}`.substr(-2),
    año = date.getFullYear();

  return `${año}-${mes}-${dia}`
}


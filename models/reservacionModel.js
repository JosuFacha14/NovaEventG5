// conexion a la base de datos
const db = require('../config/db');
 
// sp_re_insert
 
// insertar espacio
const insEspacio = (datos, callback) => {
  const { nom_espacio, can_capacidad, tip_espacio, ind_estado, mon_precio_hora } = datos;
  db.query(
    'CALL SP_RE_INSERT(?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    ['INS_RE_ESPACIO', nom_espacio, can_capacidad, tip_espacio, ind_estado, mon_precio_hora],
    callback
  );
};
 
// insertar reservacion
const insReservacion = (datos, callback) => {
  const { cod_espacio, cod_cliente, cod_empleado, fec_inicio, fec_fin, ind_estado, des_notas, usr_ingreso } = datos;
  db.query(
    'CALL SP_RE_INSERT(?, NULL, NULL, NULL, ?, NULL, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL)',
    ['INS_RE_RESERVACION', ind_estado, cod_espacio, cod_cliente, cod_empleado, fec_inicio, fec_fin, des_notas, usr_ingreso],
    callback
  );
};
 
// insertar espacio ocupado
const insEspacioOcupado = (datos, callback) => {
  const { cod_espacio, fec_inicio, fec_fin, des_motivo, usr_ingreso } = datos;
  db.query(
    'CALL SP_RE_INSERT(?, NULL, NULL, NULL, NULL, NULL, ?, NULL, NULL, ?, ?, NULL, ?, ?, NULL, NULL, NULL, NULL)',
    ['INS_RE_ESPACIO_OCUPADO', cod_espacio, fec_inicio, fec_fin, usr_ingreso, des_motivo],
    callback
  );
};
 
// insertar historial de reservacion
const insHistorialReservacion = (datos, callback) => {
  const { cod_reservacion, ind_estado_ant, ind_estado_nue, cod_persona_cam } = datos;
  db.query(
    'CALL SP_RE_INSERT(?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?, ?, ?, ?)',
    ['INS_RE_HISTORIAL_RESERVACION', cod_reservacion, ind_estado_ant, ind_estado_nue, cod_persona_cam],
    callback
  );
};
 
// sp_re_update
 
// actualizar espacio
const updEspacio = (datos, callback) => {
  const { cod_espacio, nom_espacio, can_capacidad, tip_espacio, ind_estado, mon_precio_hora } = datos;
  db.query(
    'CALL SP_RE_UPDATE(?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    ['UPD_RE_ESPACIO', cod_espacio, nom_espacio, can_capacidad, tip_espacio, ind_estado, mon_precio_hora],
    callback
  );
};
 
// actualizar reservacion
const updReservacion = (datos, callback) => {
  const { cod_reservacion, cod_espacio, cod_cliente, cod_empleado, fec_inicio, fec_fin, ind_estado, des_notas } = datos;
  db.query(
    'CALL SP_RE_UPDATE(?, ?, NULL, NULL, NULL, ?, NULL, ?, ?, ?, ?, ?, ?, NULL, NULL)',
    ['UPD_RE_RESERVACION', cod_espacio, ind_estado, cod_reservacion, cod_cliente, cod_empleado, fec_inicio, fec_fin, des_notas],
    callback
  );
};
 
// actualizar espacio ocupado
const updEspacioOcupado = (datos, callback) => {
  const { cod_espa_ocup, cod_espacio, fec_inicio, fec_fin, des_motivo } = datos;
  db.query(
    'CALL SP_RE_UPDATE(?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?, ?, NULL, ?, ?)',
    ['UPD_RE_ESPACIO_OCUPADO', cod_espacio, fec_inicio, fec_fin, cod_espa_ocup, des_motivo],
    callback
  );
};
 
// soft delete espacio (cambio de estado)
const softDeleteEspacio = (datos, callback) => {
  const { cod_espacio, ind_estado } = datos;
  db.query(
    'CALL SP_RE_UPDATE(?, ?, NULL, NULL, NULL, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    ['UPD_RE_SOFT_DELETE_ESPACIO', cod_espacio, ind_estado],
    callback
  );
};
 
// soft delete reservacion (cambio de estado)
const softDeleteReservacion = (datos, callback) => {
  const { cod_reservacion, ind_estado } = datos;
  db.query(
    'CALL SP_RE_UPDATE(?, NULL, NULL, NULL, NULL, ?, NULL, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    ['UPD_RE_SOFT_DELETE_RESERVACION', ind_estado, cod_reservacion],
    callback
  );
};
 
// sp_re_select
 
// obtener espacios
const selEspacio = (cod_espacio, callback) => {
  db.query(
    'CALL SP_RE_SELECT(?, ?, NULL, NULL, NULL)',
    ['SEL_RE_ESPACIO', cod_espacio || null],
    callback
  );
};
 
// obtener reservaciones
const selReservacion = (cod_reservacion, callback) => {
  db.query(
    'CALL SP_RE_SELECT(?, NULL, ?, NULL, NULL)',
    ['SEL_RE_RESERVACION', cod_reservacion || null],
    callback
  );
};
 
// obtener espacios ocupados
const selEspacioOcupado = (cod_espa_ocup, callback) => {
  db.query(
    'CALL SP_RE_SELECT(?, NULL, NULL, ?, NULL)',
    ['SEL_RE_ESPACIO_OCUPADO', cod_espa_ocup || null],
    callback
  );
};
 
// obtener historial de reservacion
const selHistorialReservacion = (cod_reservacion, csc_historial, callback) => {
  db.query(
    'CALL SP_RE_SELECT(?, NULL, ?, NULL, ?)',
    ['SEL_RE_HISTORIAL_RESERVACION', cod_reservacion || null, csc_historial || null],
    callback
  );
};
 
module.exports = {
  insEspacio,
  insReservacion,
  insEspacioOcupado,
  insHistorialReservacion,
  updEspacio,
  updReservacion,
  updEspacioOcupado,
  softDeleteEspacio,
  softDeleteReservacion,
  selEspacio,
  selReservacion,
  selEspacioOcupado,
  selHistorialReservacion
};
 
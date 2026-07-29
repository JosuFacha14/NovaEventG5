// conexion a la base de datos
const db = require('../config/db');

// sp_in_insert

// insertar item de inventario (puede incluir categoria, almacen, reserva y asignacion en el mismo registro)
const insItem = (datos, callback) => {
  const {
    cod_categoria, nom_categoria, des_categoria, des_icono,
    cod_almacen, nom_almacen, dir_ubicacion, cod_empleado, can_capacidad,
    cod_item, nom_item, des_item, can_total, can_disponible, cod_item_unico, img_foto_url, fec_adquisicion, mon_costo,
    cod_reserva, cod_evento_res, can_reservada, fec_inicio_res, fec_fin_res, nom_solicitante, des_notas_res,
    cod_asignacion, cod_evento_asig, can_asignada, fec_salida, fec_retorno, nom_resp_asig, des_observaciones,
    usr_registro
  } = datos;

  db.query(
    'CALL INSERT_INVENTARIO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
    [
      cod_categoria || null, nom_categoria || null, des_categoria || null, des_icono || null,
      cod_almacen || null, nom_almacen || null, dir_ubicacion || null, cod_empleado || null, can_capacidad || null,
      cod_item || null, nom_item, des_item || null, can_total, can_disponible, cod_item_unico || null, img_foto_url || null, fec_adquisicion || null, mon_costo || null,
      cod_reserva || null, cod_evento_res || null, can_reservada || null, fec_inicio_res || null, fec_fin_res || null, nom_solicitante || null, des_notas_res || null,
      cod_asignacion || null, cod_evento_asig || null, can_asignada || null, fec_salida || null, fec_retorno || null, nom_resp_asig || null, des_observaciones || null,
      usr_registro
    ],
    callback
  );
};

// sp_in_update

// actualizar item
const updItem = (datos, callback) => {
  const { usr_registro, cod_item, nom_item, des_item, can_total, can_disponible, ind_estado, cod_item_unico, img_foto_url, fec_adquisicion, mon_costo } = datos;
  db.query(
    'CALL SP_IN_UPDATE(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    [usr_registro, cod_item, nom_item || null, des_item || null, can_total || null, can_disponible || null, ind_estado || null, cod_item_unico || null, img_foto_url || null, fec_adquisicion || null, mon_costo || null],
    callback
  );
};

// actualizar categoria
const updCategoria = (datos, callback) => {
  const { usr_registro, cod_categoria, nom_categoria, des_categoria, des_icono } = datos;
  db.query(
    'CALL SP_IN_UPDATE(NULL, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    [usr_registro, cod_categoria, nom_categoria, des_categoria || null, des_icono || null],
    callback
  );
};

// actualizar almacen
const updAlmacen = (datos, callback) => {
  const { usr_registro, cod_almacen, nom_almacen, dir_ubicacion, cod_empleado, can_capacidad } = datos;
  db.query(
    'CALL SP_IN_UPDATE(NULL, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    [usr_registro, cod_almacen, nom_almacen, dir_ubicacion || null, cod_empleado || null, can_capacidad || null],
    callback
  );
};

// actualizar reserva de inventario (incluye cancelacion, que repone la cantidad disponible del item)
const updReserva = (datos, callback) => {
  const { usr_registro, cod_reserva, can_reservada, fec_inicio_res, fec_fin_res, ind_estado_res, nom_solicitante, des_notas_res } = datos;
  db.query(
    'CALL SP_IN_UPDATE(NULL, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    [usr_registro, cod_reserva, can_reservada || null, fec_inicio_res || null, fec_fin_res || null, ind_estado_res || null, nom_solicitante || null, des_notas_res || null],
    callback
  );
};

// actualizar asignacion a evento (retornado/perdido ajustan automaticamente las cantidades del item)
const updAsignacion = (datos, callback) => {
  const { usr_registro, cod_asignacion, can_asignada, fec_salida, fec_retorno, ind_estado_asig, ind_condicion, nom_resp_asig, des_observaciones } = datos;
  db.query(
    'CALL SP_IN_UPDATE(NULL, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?, ?, ?, ?, ?, ?, ?, ?)',
    [usr_registro, cod_asignacion, can_asignada || null, fec_salida || null, fec_retorno || null, ind_estado_asig || null, ind_condicion || null, nom_resp_asig || null, des_observaciones || null],
    callback
  );
};

// soft delete item (ind_estado = BAJA)
const softDeleteItem = (datos, callback) => {
  const { usr_registro, cod_item } = datos;
  db.query(
    'CALL SP_IN_UPDATE(?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    ['DEL_IN_ITEM', usr_registro, cod_item],
    callback
  );
};

// soft delete categoria (ind_activa = false)
const softDeleteCategoria = (datos, callback) => {
  const { usr_registro, cod_categoria } = datos;
  db.query(
    'CALL SP_IN_UPDATE(?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    ['DEL_IN_CATEGORIA', usr_registro, cod_categoria],
    callback
  );
};

// soft delete almacen (ind_activo = false)
const softDeleteAlmacen = (datos, callback) => {
  const { usr_registro, cod_almacen } = datos;
  db.query(
    'CALL SP_IN_UPDATE(?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)',
    ['DEL_IN_ALMACEN', usr_registro, cod_almacen],
    callback
  );
};

// sp_in_select

// obtener inventario con filtros opcionales (item, categoria, almacen o evento)
const selInventario = (filtros, callback) => {
  const { cod_item, cod_categoria, cod_almacen, cod_evento } = filtros || {};
  db.query(
    'CALL SEL_ALL_INVENTARIO(?, ?, ?, ?)',
    [cod_item || null, cod_categoria || null, cod_almacen || null, cod_evento || null],
    callback
  );
};
// Listar eventos activos (lectura de ve_eventos para dropdowns)
const selEventos = (callback) => {
  db.query(
    "CALL SP_SELECT_VENTAS('SEL_EVENTO')",
    callback
  );
};

module.exports = {
  insItem,
  updItem,
  updCategoria,
  updAlmacen,
  updReserva,
  updAsignacion,
  softDeleteItem,
  softDeleteCategoria,
  softDeleteAlmacen,
  selInventario,
  selEventos
};
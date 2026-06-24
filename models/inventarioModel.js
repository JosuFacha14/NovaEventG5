// conexion a la base de datos
const db = require('../config/db');

/* ==========================
   ALMACENES
========================== */

const insAlmacen = (datos, callback) => {
  const { NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO, CAN_CAPACIDAD, IND_ACTIVO, USR_REGISTRO } = datos;
  db.query(
    `INSERT INTO in_almacenes
      (NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO, CAN_CAPACIDAD, IND_ACTIVO, USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, ?, NOW())`,
    [NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO || null, CAN_CAPACIDAD, IND_ACTIVO || 1, USR_REGISTRO],
    callback
  );
};

const updAlmacen = (datos, callback) => {
  const { cod_almacen, NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO, CAN_CAPACIDAD, IND_ACTIVO } = datos;
  db.query(
    `UPDATE in_almacenes SET
      NOM_ALMACEN = ?, DIR_UBICACION = ?, COD_EMPLEADO = ?, CAN_CAPACIDAD = ?, IND_ACTIVO = ?
     WHERE COD_ALMACEN = ?`,
    [NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO || null, CAN_CAPACIDAD, IND_ACTIVO, cod_almacen],
    callback
  );
};

/* ==========================
   CATEGORIAS
========================== */

const insCategoria = (datos, callback) => {
  const { NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO, IND_ACTIVA, USR_REGISTRO } = datos;
  db.query(
    `INSERT INTO in_categorias_inventario
      (NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO, IND_ACTIVA, USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, NOW())`,
    [NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO, IND_ACTIVA || 1, USR_REGISTRO],
    callback
  );
};

const updCategoria = (datos, callback) => {
  const { cod_categoria, NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO, IND_ACTIVA } = datos;
  db.query(
    `UPDATE in_categorias_inventario SET
      NOM_CATEGORIA = ?, DES_CATEGORIA = ?, DES_ICONO = ?, IND_ACTIVA = ?
     WHERE COD_CATEGORIA = ?`,
    [NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO, IND_ACTIVA, cod_categoria],
    callback
  );
};

/* ==========================
   ITEMS
========================== */

const insItem = (datos, callback) => {
  const { NOM_ITEM, DES_ITEM, COD_CATEGORIA, COD_ALMACEN, CAN_TOTAL, CAN_DISPONIBLE, IND_ESTADO, COD_ITEM_UNICO, IMG_FOTO_URL, FEC_ADQUISICION, MON_COSTO, USR_REGISTRO } = datos;
  db.query(
    `INSERT INTO in_inventario_item
      (NOM_ITEM, DES_ITEM, COD_CATEGORIA, COD_ALMACEN, CAN_TOTAL, CAN_DISPONIBLE,
       IND_ESTADO, COD_ITEM_UNICO, IMG_FOTO_URL, FEC_ADQUISICION, MON_COSTO,
       USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())`,
    [NOM_ITEM, DES_ITEM, COD_CATEGORIA, COD_ALMACEN, CAN_TOTAL, CAN_DISPONIBLE || CAN_TOTAL, IND_ESTADO || 'ACTIVO', COD_ITEM_UNICO, IMG_FOTO_URL, FEC_ADQUISICION, MON_COSTO, USR_REGISTRO],
    callback
  );
};

const updItem = (datos, callback) => {
  const { cod_item, NOM_ITEM, DES_ITEM, COD_CATEGORIA, COD_ALMACEN, CAN_TOTAL, CAN_DISPONIBLE, IND_ESTADO, COD_ITEM_UNICO, IMG_FOTO_URL, FEC_ADQUISICION, MON_COSTO } = datos;
  db.query(
    `UPDATE in_inventario_item SET
      NOM_ITEM = ?, DES_ITEM = ?, COD_CATEGORIA = ?, COD_ALMACEN = ?,
      CAN_TOTAL = ?, CAN_DISPONIBLE = ?, IND_ESTADO = ?, COD_ITEM_UNICO = ?,
      IMG_FOTO_URL = ?, FEC_ADQUISICION = ?, MON_COSTO = ?
     WHERE COD_ITEM = ?`,
    [NOM_ITEM, DES_ITEM, COD_CATEGORIA, COD_ALMACEN, CAN_TOTAL, CAN_DISPONIBLE, IND_ESTADO, COD_ITEM_UNICO, IMG_FOTO_URL, FEC_ADQUISICION, MON_COSTO, cod_item],
    callback
  );
};

/* ==========================
   ASIGNACIONES
========================== */

const insAsignacion = (datos, callback) => {
  const { COD_EVENTO, COD_ITEM, CAN_ASIGNADA, FEC_SALIDA, FEC_RETORNO, IND_ESTADO, IND_CONDICION, NOM_RESPONSABLE, DES_OBSERVACIONES, USR_REGISTRO } = datos;
  db.query(
    `INSERT INTO in_asignacion_evento
      (COD_EVENTO, COD_ITEM, CAN_ASIGNADA, FEC_SALIDA, FEC_RETORNO,
       IND_ESTADO, IND_CONDICION, NOM_RESPONSABLE, DES_OBSERVACIONES,
       USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())`,
    [COD_EVENTO, COD_ITEM, CAN_ASIGNADA, FEC_SALIDA, FEC_RETORNO || null, IND_ESTADO || 'PENDIENTE', IND_CONDICION || 'BUENO', NOM_RESPONSABLE, DES_OBSERVACIONES, USR_REGISTRO],
    callback
  );
};

const updAsignacion = (datos, callback) => {
  const { cod_asignacion, COD_EVENTO, COD_ITEM, CAN_ASIGNADA, FEC_SALIDA, FEC_RETORNO, IND_ESTADO, IND_CONDICION, NOM_RESPONSABLE, DES_OBSERVACIONES } = datos;
  db.query(
    `UPDATE in_asignacion_evento SET
      COD_EVENTO = ?, COD_ITEM = ?, CAN_ASIGNADA = ?, FEC_SALIDA = ?,
      FEC_RETORNO = ?, IND_ESTADO = ?, IND_CONDICION = ?,
      NOM_RESPONSABLE = ?, DES_OBSERVACIONES = ?
     WHERE COD_ASIGNACION = ?`,
    [COD_EVENTO, COD_ITEM, CAN_ASIGNADA, FEC_SALIDA, FEC_RETORNO, IND_ESTADO, IND_CONDICION, NOM_RESPONSABLE, DES_OBSERVACIONES, cod_asignacion],
    callback
  );
};

/* ==========================
   RESERVAS
========================== */

const insReserva = (datos, callback) => {
  const { COD_EVENTO, COD_ITEM, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA, IND_ESTADO_RESERVA, NOM_SOLICITANTE, DES_NOTAS, USR_REGISTRO } = datos;
  db.query(
    `INSERT INTO in_reservas_inventario
      (COD_EVENTO, COD_ITEM, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA,
       IND_ESTADO_RESERVA, NOM_SOLICITANTE, DES_NOTAS, USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())`,
    [COD_EVENTO, COD_ITEM, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA, IND_ESTADO_RESERVA || 'ACTIVA', NOM_SOLICITANTE, DES_NOTAS, USR_REGISTRO],
    callback
  );
};

const updReserva = (datos, callback) => {
  const { cod_reserva, COD_EVENTO, COD_ITEM, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA, IND_ESTADO_RESERVA, NOM_SOLICITANTE, DES_NOTAS } = datos;
  db.query(
    `UPDATE in_reservas_inventario SET
      COD_EVENTO = ?, COD_ITEM = ?, CAN_RESERVADA = ?, FEC_INICIO_RESERVA = ?,
      FEC_FIN_RESERVA = ?, IND_ESTADO_RESERVA = ?, NOM_SOLICITANTE = ?, DES_NOTAS = ?
     WHERE COD_RESERVA = ?`,
    [COD_EVENTO, COD_ITEM, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA, IND_ESTADO_RESERVA, NOM_SOLICITANTE, DES_NOTAS, cod_reserva],
    callback
  );
};

module.exports = {
  insAlmacen, updAlmacen,
  insCategoria, updCategoria,
  insItem, updItem,
  insAsignacion, updAsignacion,
  insReserva, updReserva
};

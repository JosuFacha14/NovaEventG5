// conexion a la base de datos
const db = require('../config/db');

/* ==================================================================
   HELPER: ejecutar un stored procedure y retornar callback estándar
================================================================== */
const callProcedure = (sql, params, callback) => {
  db.query(sql, params, (err, results) => {
    if (err) return callback(err);
    // Los SP devuelven un array de result-sets; tomamos el primero con datos
    const data = Array.isArray(results)
      ? results.find(r => Array.isArray(r)) ?? results[0]
      : results;
    callback(null, data);
  });
};
 
/* ==========================
   GET  →  SEL_ALL_INVENTARIO
   Parámetros: (cod_item, cod_categoria, cod_almacen, cod_evento)
   Pasar NULL en los que no apliquen.
========================== */
 
// Todos los items (sin filtro)
const getAllItems = (callback) => {
  callProcedure(
    'CALL SEL_ALL_INVENTARIO(?, ?, ?, ?)',
    [null, null, null, null],
    callback
  );
};
 
// Item por código
const getItemById = (cod_item, callback) => {
  callProcedure(
    'CALL SEL_ALL_INVENTARIO(?, ?, ?, ?)',
    [cod_item, null, null, null],
    callback
  );
};
 
// Items por categoría
const getItemsByCategoria = (cod_categoria, callback) => {
  callProcedure(
    'CALL SEL_ALL_INVENTARIO(?, ?, ?, ?)',
    [null, cod_categoria, null, null],
    callback
  );
};
 
// Items por almacén
const getItemsByAlmacen = (cod_almacen, callback) => {
  callProcedure(
    'CALL SEL_ALL_INVENTARIO(?, ?, ?, ?)',
    [null, null, cod_almacen, null],
    callback
  );
};
 
// Items por evento (reservas + asignaciones)
const getItemsByEvento = (cod_evento, callback) => {
  callProcedure(
    'CALL SEL_ALL_INVENTARIO(?, ?, ?, ?)',
    [null, null, null, cod_evento],
    callback
  );
};
 
/* ==========================
   POST  →  INSERT_INVENTARIO
   Inserta ítem y opcionalmente categoría, almacén, reserva y asignación.
========================== */
const insertInventario = (datos, callback) => {
  const {
    // Categoría (opcional)
    COD_CATEGORIA, NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO,
    // Almacén (opcional)
    COD_ALMACEN, NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO, CAN_CAPACIDAD,
    // Ítem (obligatorio)
    COD_ITEM, NOM_ITEM, DES_ITEM,
    CAN_TOTAL, CAN_DISPONIBLE, COD_ITEM_UNICO, IMG_FOTO_URL,
    FEC_ADQUISICION, MON_COSTO,
    // Reserva (opcional)
    COD_RESERVA, COD_EVENTO_RES, CAN_RESERVADA,
    FEC_INICIO_RESERVA, FEC_FIN_RESERVA, NOM_SOLICITANTE, DES_NOTAS,
    // Asignación (opcional)
    COD_ASIGNACION, COD_EVENTO_ASIG, CAN_ASIGNADA,
    FEC_SALIDA, FEC_RETORNO, NOM_RESPONSABLE, DES_OBSERVACIONES,
    // Usuario
    USR_REGISTRO
  } = datos;
 
  callProcedure(
    `CALL INSERT_INVENTARIO(
      ?, ?, ?, ?,
      ?, ?, ?, ?, ?,
      ?, ?, ?, ?, ?, ?, ?, ?, ?,
      ?, ?, ?, ?, ?, ?, ?,
      ?, ?, ?, ?, ?, ?, ?, ?)`,
    [
      COD_CATEGORIA     || null, NOM_CATEGORIA  || null, DES_CATEGORIA  || null, DES_ICONO      || null,
      COD_ALMACEN       || null, NOM_ALMACEN    || null, DIR_UBICACION  || null,
      COD_EMPLEADO      || null, CAN_CAPACIDAD  || null,
      COD_ITEM,                  NOM_ITEM,                DES_ITEM,
      CAN_TOTAL,                 CAN_DISPONIBLE || CAN_TOTAL,
      COD_ITEM_UNICO    || null, IMG_FOTO_URL   || null,
      FEC_ADQUISICION   || null, MON_COSTO      || null,
      COD_RESERVA       || null, COD_EVENTO_RES || null, CAN_RESERVADA  || null,
      FEC_INICIO_RESERVA|| null, FEC_FIN_RESERVA|| null,
      NOM_SOLICITANTE   || null, DES_NOTAS      || null,
      COD_ASIGNACION    || null, COD_EVENTO_ASIG|| null, CAN_ASIGNADA   || null,
      FEC_SALIDA        || null, FEC_RETORNO    || null,
      NOM_RESPONSABLE   || null, DES_OBSERVACIONES || null,
      USR_REGISTRO
    ],
    callback
  );
};
 
/* ==========================
   PUT  →  SP_IN_UPDATE
   Cubre UPDATE normal y Soft Delete según PV_ACCION:
     null            → UPDATE de los campos que se pasen
     'DEL_IN_ITEM'       → Soft delete de ítem
     'DEL_IN_CATEGORIA'  → Soft delete de categoría
     'DEL_IN_ALMACEN'    → Soft delete de almacén
========================== */
const updateInventario = (datos, callback) => {
  const {
    ACCION,
    USR_REGISTRO,
    // Ítem
    COD_ITEM, NOM_ITEM, DES_ITEM,
    CAN_TOTAL, CAN_DISPONIBLE, IND_ESTADO,
    COD_ITEM_UNICO, IMG_FOTO_URL, FEC_ADQUISICION, MON_COSTO,
    // Categoría
    COD_CATEGORIA, NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO,
    // Almacén
    COD_ALMACEN, NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO, CAN_CAPACIDAD,
    // Reserva
    COD_RESERVA, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA,
    IND_ESTADO_RESERVA, NOM_SOLICITANTE, DES_NOTAS,
    // Asignación
    COD_ASIGNACION, CAN_ASIGNADA, FEC_SALIDA, FEC_RETORNO,
    IND_ESTADO_ASIG, IND_CONDICION, NOM_RESPONSABLE, DES_OBSERVACIONES
  } = datos;
 
  callProcedure(
    `CALL SP_IN_UPDATE(
      ?, ?,
      ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
      ?, ?, ?, ?,
      ?, ?, ?, ?, ?,
      ?, ?, ?, ?, ?, ?, ?,
      ?, ?, ?, ?, ?, ?, ?, ?)`,
    [
      ACCION        || null, USR_REGISTRO,
      // Ítem
      COD_ITEM      || null, NOM_ITEM          || null, DES_ITEM          || null,
      CAN_TOTAL     || null, CAN_DISPONIBLE     || null, IND_ESTADO        || null,
      COD_ITEM_UNICO|| null, IMG_FOTO_URL       || null,
      FEC_ADQUISICION|| null, MON_COSTO         || null,
      // Categoría
      COD_CATEGORIA || null, NOM_CATEGORIA      || null,
      DES_CATEGORIA || null, DES_ICONO          || null,
      // Almacén
      COD_ALMACEN   || null, NOM_ALMACEN        || null,
      DIR_UBICACION || null, COD_EMPLEADO        || null, CAN_CAPACIDAD    || null,
      // Reserva
      COD_RESERVA   || null, CAN_RESERVADA       || null,
      FEC_INICIO_RESERVA || null, FEC_FIN_RESERVA|| null,
      IND_ESTADO_RESERVA || null, NOM_SOLICITANTE|| null, DES_NOTAS        || null,
      // Asignación
      COD_ASIGNACION|| null, CAN_ASIGNADA        || null,
      FEC_SALIDA    || null, FEC_RETORNO          || null,
      IND_ESTADO_ASIG || null, IND_CONDICION      || null,
      NOM_RESPONSABLE || null, DES_OBSERVACIONES  || null
    ],
    callback
  );
};
 
module.exports = {
  // GET
  getAllItems,
  getItemById,
  getItemsByCategoria,
  getItemsByAlmacen,
  getItemsByEvento,
  // POST
  insertInventario,
  // PUT (update + soft delete)
  updateInventario
};

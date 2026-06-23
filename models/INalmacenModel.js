const db = require('../db');

const TABLE = 'in_almacenes';

async function getById(id) {
  const rows = await db.queryAsync(`SELECT * FROM ${TABLE} WHERE COD_ALMACEN = ?`, [id]);
  return rows[0];
}

async function create(data) {
  const {
    NOM_ALMACEN,
    DIR_UBICACION,
    COD_EMPLEADO,
    CAN_CAPACIDAD,
    IND_ACTIVO,
    USR_REGISTRO
  } = data;

  const result = await db.queryAsync(
    `INSERT INTO ${TABLE}
      (NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO, CAN_CAPACIDAD, IND_ACTIVO, USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, ?, NOW())`,
    [NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO || null, CAN_CAPACIDAD, IND_ACTIVO ?? 1, USR_REGISTRO]
  );

  return getById(result.insertId);
}

async function update(id, data) {
  const {
    NOM_ALMACEN,
    DIR_UBICACION,
    COD_EMPLEADO,
    CAN_CAPACIDAD,
    IND_ACTIVO
  } = data;

  await db.queryAsync(
    `UPDATE ${TABLE} SET
      NOM_ALMACEN = ?,
      DIR_UBICACION = ?,
      COD_EMPLEADO = ?,
      CAN_CAPACIDAD = ?,
      IND_ACTIVO = ?
     WHERE COD_ALMACEN = ?`,
    [NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO || null, CAN_CAPACIDAD, IND_ACTIVO, id]
  );

  return getById(id);
}

module.exports = { getById, create, update };
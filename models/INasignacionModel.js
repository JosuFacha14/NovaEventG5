const db = require('../db');

const TABLE = 'in_asignacion_evento';

async function getById(id) {
  const rows = await db.queryAsync(`SELECT * FROM ${TABLE} WHERE COD_ASIGNACION = ?`, [id]);
  return rows[0];
}

async function create(data) {
  const {
    COD_EVENTO,
    COD_ITEM,
    CAN_ASIGNADA,
    FEC_SALIDA,
    FEC_RETORNO,
    IND_ESTADO,
    IND_CONDICION,
    NOM_RESPONSABLE,
    DES_OBSERVACIONES,
    USR_REGISTRO
  } = data;

  const result = await db.queryAsync(
    `INSERT INTO ${TABLE}
      (COD_EVENTO, COD_ITEM, CAN_ASIGNADA, FEC_SALIDA, FEC_RETORNO,
       IND_ESTADO, IND_CONDICION, NOM_RESPONSABLE, DES_OBSERVACIONES,
       USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())`,
    [
      COD_EVENTO,
      COD_ITEM,
      CAN_ASIGNADA,
      FEC_SALIDA,
      FEC_RETORNO || null,
      IND_ESTADO || 'PENDIENTE',
      IND_CONDICION || 'BUENO',
      NOM_RESPONSABLE,
      DES_OBSERVACIONES,
      USR_REGISTRO
    ]
  );

  return getById(result.insertId);
}

async function update(id, data) {
  const {
    COD_EVENTO,
    COD_ITEM,
    CAN_ASIGNADA,
    FEC_SALIDA,
    FEC_RETORNO,
    IND_ESTADO,
    IND_CONDICION,
    NOM_RESPONSABLE,
    DES_OBSERVACIONES
  } = data;

  await db.queryAsync(
    `UPDATE ${TABLE} SET
      COD_EVENTO = ?,
      COD_ITEM = ?,
      CAN_ASIGNADA = ?,
      FEC_SALIDA = ?,
      FEC_RETORNO = ?,
      IND_ESTADO = ?,
      IND_CONDICION = ?,
      NOM_RESPONSABLE = ?,
      DES_OBSERVACIONES = ?
     WHERE COD_ASIGNACION = ?`,
    [
      COD_EVENTO,
      COD_ITEM,
      CAN_ASIGNADA,
      FEC_SALIDA,
      FEC_RETORNO,
      IND_ESTADO,
      IND_CONDICION,
      NOM_RESPONSABLE,
      DES_OBSERVACIONES,
      id
    ]
  );

  return getById(id);
}

module.exports = { getById, create, update };
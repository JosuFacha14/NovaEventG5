const db = require('../db');

const TABLE = 'in_reservas_inventario';

async function getById(id) {
  const rows = await db.queryAsync(`SELECT * FROM ${TABLE} WHERE COD_RESERVA = ?`, [id]);
  return rows[0];
}

async function create(data) {
  const {
    COD_EVENTO,
    COD_ITEM,
    CAN_RESERVADA,
    FEC_INICIO_RESERVA,
    FEC_FIN_RESERVA,
    IND_ESTADO_RESERVA,
    NOM_SOLICITANTE,
    DES_NOTAS,
    USR_REGISTRO
  } = data;

  const result = await db.queryAsync(
    `INSERT INTO ${TABLE}
      (COD_EVENTO, COD_ITEM, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA,
       IND_ESTADO_RESERVA, NOM_SOLICITANTE, DES_NOTAS, USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())`,
    [
      COD_EVENTO,
      COD_ITEM,
      CAN_RESERVADA,
      FEC_INICIO_RESERVA,
      FEC_FIN_RESERVA,
      IND_ESTADO_RESERVA || 'ACTIVA',
      NOM_SOLICITANTE,
      DES_NOTAS,
      USR_REGISTRO
    ]
  );

  return getById(result.insertId);
}

async function update(id, data) {
  const {
    COD_EVENTO,
    COD_ITEM,
    CAN_RESERVADA,
    FEC_INICIO_RESERVA,
    FEC_FIN_RESERVA,
    IND_ESTADO_RESERVA,
    NOM_SOLICITANTE,
    DES_NOTAS
  } = data;

  await db.queryAsync(
    `UPDATE ${TABLE} SET
      COD_EVENTO = ?,
      COD_ITEM = ?,
      CAN_RESERVADA = ?,
      FEC_INICIO_RESERVA = ?,
      FEC_FIN_RESERVA = ?,
      IND_ESTADO_RESERVA = ?,
      NOM_SOLICITANTE = ?,
      DES_NOTAS = ?
     WHERE COD_RESERVA = ?`,
    [
      COD_EVENTO,
      COD_ITEM,
      CAN_RESERVADA,
      FEC_INICIO_RESERVA,
      FEC_FIN_RESERVA,
      IND_ESTADO_RESERVA,
      NOM_SOLICITANTE,
      DES_NOTAS,
      id
    ]
  );

  return getById(id);
}

module.exports = { getById, create, update };
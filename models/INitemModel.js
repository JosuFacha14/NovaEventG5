const db = require('../db');

const TABLE = 'in_inventario_item';

async function getById(id) {
  const rows = await db.queryAsync(`SELECT * FROM ${TABLE} WHERE COD_ITEM = ?`, [id]);
  return rows[0];
}

async function create(data) {
  const {
    NOM_ITEM,
    DES_ITEM,
    COD_CATEGORIA,
    COD_ALMACEN,
    CAN_TOTAL,
    CAN_DISPONIBLE,
    IND_ESTADO,
    COD_ITEM_UNICO,
    IMG_FOTO_URL,
    FEC_ADQUISICION,
    MON_COSTO,
    USR_REGISTRO
  } = data;

  const result = await db.queryAsync(
    `INSERT INTO ${TABLE}
      (NOM_ITEM, DES_ITEM, COD_CATEGORIA, COD_ALMACEN, CAN_TOTAL, CAN_DISPONIBLE,
       IND_ESTADO, COD_ITEM_UNICO, IMG_FOTO_URL, FEC_ADQUISICION, MON_COSTO,
       USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())`,
    [
      NOM_ITEM,
      DES_ITEM,
      COD_CATEGORIA,
      COD_ALMACEN,
      CAN_TOTAL,
      CAN_DISPONIBLE ?? CAN_TOTAL,
      IND_ESTADO || 'ACTIVO',
      COD_ITEM_UNICO,
      IMG_FOTO_URL,
      FEC_ADQUISICION,
      MON_COSTO,
      USR_REGISTRO
    ]
  );

  return getById(result.insertId);
}

async function update(id, data) {
  const {
    NOM_ITEM,
    DES_ITEM,
    COD_CATEGORIA,
    COD_ALMACEN,
    CAN_TOTAL,
    CAN_DISPONIBLE,
    IND_ESTADO,
    COD_ITEM_UNICO,
    IMG_FOTO_URL,
    FEC_ADQUISICION,
    MON_COSTO
  } = data;

  await db.queryAsync(
    `UPDATE ${TABLE} SET
      NOM_ITEM = ?,
      DES_ITEM = ?,
      COD_CATEGORIA = ?,
      COD_ALMACEN = ?,
      CAN_TOTAL = ?,
      CAN_DISPONIBLE = ?,
      IND_ESTADO = ?,
      COD_ITEM_UNICO = ?,
      IMG_FOTO_URL = ?,
      FEC_ADQUISICION = ?,
      MON_COSTO = ?
     WHERE COD_ITEM = ?`,
    [
      NOM_ITEM,
      DES_ITEM,
      COD_CATEGORIA,
      COD_ALMACEN,
      CAN_TOTAL,
      CAN_DISPONIBLE,
      IND_ESTADO,
      COD_ITEM_UNICO,
      IMG_FOTO_URL,
      FEC_ADQUISICION,
      MON_COSTO,
      id
    ]
  );

  return getById(id);
}

module.exports = { getById, create, update };
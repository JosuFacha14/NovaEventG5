const db = require('../db');

const TABLE = 'in_categorias_inventario';

async function getById(id) {
  const rows = await db.queryAsync(`SELECT * FROM ${TABLE} WHERE COD_CATEGORIA = ?`, [id]);
  return rows[0];
}

async function create(data) {
  const {
    NOM_CATEGORIA,
    DES_CATEGORIA,
    DES_ICONO,
    IND_ACTIVA,
    USR_REGISTRO
  } = data;

  const result = await db.queryAsync(
    `INSERT INTO ${TABLE}
      (NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO, IND_ACTIVA, USR_REGISTRO, FEC_REGISTRO)
     VALUES (?, ?, ?, ?, ?, NOW())`,
    [NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO, IND_ACTIVA ?? 1, USR_REGISTRO]
  );

  return getById(result.insertId);
}

async function update(id, data) {
  const {
    NOM_CATEGORIA,
    DES_CATEGORIA,
    DES_ICONO,
    IND_ACTIVA
  } = data;

  await db.queryAsync(
    `UPDATE ${TABLE} SET
      NOM_CATEGORIA = ?,
      DES_CATEGORIA = ?,
      DES_ICONO = ?,
      IND_ACTIVA = ?
     WHERE COD_CATEGORIA = ?`,
    [NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO, IND_ACTIVA, id]
  );

  return getById(id);
}

module.exports = { getById, create, update };
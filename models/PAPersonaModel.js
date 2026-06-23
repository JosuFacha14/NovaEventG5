const db = require('../db');

// Llama al SP UPD_PERSONAS con acción UPDATE
async function update(codPersona, data) {
  const {
    DNI,
    PRIMER_NOMBRE,
    SEGUNDO_NOMBRE,
    APELLIDO,
    SEXO,
    EST_CIVIL,
    EDAD,
    NUM_AREA_CEL,
    NUM_TELEFONO_CEL,
    NUM_AREA_OFI,
    NUM_TELEFONO_OFI,
    USUARIO_CORREO,
    SERVIDOR_CORREO,
    NOMBRE_USR,
    CLAVE,
    TOKEN,
    IND_USR,
    IND_PRIMER_ING,
    NOM_EMPRESA_CLI,
    IND_CLIENTE,
    CARGO,
    FEC_CONTRATACION,
    SALARIO,
    EMPRESA_PROV,
    CATEGORIA_SERV,
    USR_INGRESO
  } = data;

  await db.queryAsync(
    `CALL UPD_PERSONAS(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
    [
      'UPDATE',
      codPersona,
      DNI || null,
      PRIMER_NOMBRE || null,
      SEGUNDO_NOMBRE !== undefined ? SEGUNDO_NOMBRE : null,
      APELLIDO || null,
      SEXO || null,
      EST_CIVIL || null,
      EDAD || null,
      NUM_AREA_CEL || null,
      NUM_TELEFONO_CEL || null,
      NUM_AREA_OFI || null,
      NUM_TELEFONO_OFI || null,
      USUARIO_CORREO || null,
      SERVIDOR_CORREO || null,
      NOMBRE_USR || null,
      CLAVE || null,
      TOKEN || null,
      IND_USR || null,
      IND_PRIMER_ING || null,
      NOM_EMPRESA_CLI || null,
      IND_CLIENTE || null,
      CARGO || null,
      FEC_CONTRATACION || null,
      SALARIO || null,
      EMPRESA_PROV || null,
      CATEGORIA_SERV || null,
      USR_INGRESO || null
    ]
  );

  // Retorna la persona actualizada
  const rows = await db.queryAsync(
    `SELECT * FROM PA_PERSONAS WHERE COD_PERSONA = ?`,
    [codPersona]
  );
  return rows[0];
}

// Llama al SP UPD_PERSONAS con acción SOFT_DELETE
async function softDelete(codPersona, usrIngreso) {
  await db.queryAsync(
    `CALL UPD_PERSONAS(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
    [
      'SOFT_DELETE',
      codPersona,
      null, null, null, null, null, null, null,
      null, null, null, null, null, null, null,
      null, null, null, null, null, null, null,
      null, null, null, null,
      usrIngreso || null
    ]
  );

  return { COD_PERSONA: codPersona, estado: 'INACTIVO' };
}

// Obtener persona por ID
async function getById(codPersona) {
  const rows = await db.queryAsync(
    `SELECT * FROM PA_PERSONAS WHERE COD_PERSONA = ?`,
    [codPersona]
  );
  return rows[0];
}

module.exports = { update, softDelete, getById };

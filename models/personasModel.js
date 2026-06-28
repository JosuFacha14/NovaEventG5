const db = require('../config/db');

/* ==========================
   GET -> SP_PA_SELECT
========================== */

const obtener = (datos, callback) => {

    db.query(
        `CALL SP_PA_SELECT(
            ?,?,?,?,?,?,?,?,?,?,?,?
        )`,
        [
            datos.accion,

            datos.cod_persona || null,
            datos.cod_telefono || null,
            datos.cod_rel_pertel || null,
            datos.cod_correo || null,
            datos.cod_rel_percor || null,
            datos.cod_tipo_usr || null,
            datos.cod_usuario || null,
            datos.cod_tipo_cli || null,
            datos.cod_cliente || null,
            datos.cod_empleado || null,
            datos.cod_proveedor || null
        ],
        callback
    );
};

/* ==========================
   POST -> SP_INSERT_PERSONAS
========================== */

const insertar = (datos, callback) => {

    db.query(
        `CALL SP_INSERT_PERSONAS(
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?
        )`,
        [

            datos.accion,

            /* PERSONAS */
            datos.dni || null,
            datos.primer_nombre || null,
            datos.segundo_nombre || null,
            datos.apellido || null,
            datos.sexo || null,
            datos.est_civil || null,
            datos.edad || null,
            datos.tip_persona || null,

            /* TELEFONOS */
            datos.num_area || null,
            datos.num_telefono || null,
            datos.tip_telefono || null,

            /* CORREOS */
            datos.usuario_correo || null,
            datos.servidor_correo || null,
            datos.tip_correo || null,

            /* TIPO USUARIO */
            datos.nom_tipo || null,
            datos.des_tipo || null,

            /* USUARIO */
            datos.cod_persona || null,
            datos.cod_tipo_usr || null,
            datos.nombre || null,
            datos.clave || null,
            datos.token || null,
            datos.ind_usr || null,
            datos.ind_primer_ing || null,

            /* TIPO CLIENTE */
            datos.nom_tipo_cli || null,
            datos.des_tipo_cli || null,
            datos.ind_tipo_cli || null,

            /* CLIENTE */
            datos.cod_tipo_cli || null,
            datos.nom_empresa || null,
            datos.ind_cliente || null,

            /* EMPLEADO */
            datos.cargo || null,
            datos.fec_contratacion || null,
            datos.salario || null,

            /* PROVEEDOR */
            datos.empresa_prov || null,
            datos.categoria_servicio || null,

            /* AUDITORIA */
            datos.usr_ingreso || null

        ],
        callback
    );
};

/* ==========================
   PUT -> UPD_PERSONAS
========================== */

const actualizar = (datos, callback) => {

    db.query(
        `CALL UPD_PERSONAS(
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?
        )`,
        [

            datos.accion,

            datos.cod_persona || null,
            datos.dni || null,
            datos.primer_nombre || null,
            datos.segundo_nombre || null,
            datos.apellido || null,
            datos.sexo || null,
            datos.est_civil || null,
            datos.edad || null,

            datos.num_area_cel || null,
            datos.num_telefono_cel || null,

            datos.num_area_ofi || null,
            datos.num_telefono_ofi || null,

            datos.usuario_correo || null,
            datos.servidor_correo || null,

            datos.nombre_usr || null,
            datos.clave || null,
            datos.token || null,
            datos.ind_usr || null,
            datos.ind_primer_ing || null,

            datos.nom_empresa_cli || null,
            datos.ind_cliente || null,

            datos.cargo || null,
            datos.fec_contratacion || null,
            datos.salario || null,

            datos.empresa_prov || null,
            datos.categoria_serv || null,

            datos.usr_ingreso || null
        ],
        callback
    );
};

/* ==========================
   PATCH -> SOFT DELETE
========================== */

const softDelete = (datos, callback) => {

    db.query(
        `CALL UPD_PERSONAS(
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?
        )`,
        [

            datos.accion || 'SOFT_DELETE',

            datos.cod_persona,

            null,
            null,
            null,
            null,
            null,
            null,
            null,

            null,
            null,

            null,
            null,

            null,
            null,

            null,
            null,
            null,
            null,
            null,

            null,
            null,

            null,
            null,
            null,

            null,
            null,

            datos.usr_ingreso

        ],
        callback
    );

};

module.exports = {
    obtener,
    insertar,
    actualizar,
    softDelete
};
const db = require('../config/db');

const obtener = (accion, callback) => {

    db.query(
        'CALL SP_SELECT_VENTAS(?)',
        [accion],
        callback
    );
};

const insertar = (datos, callback) => {

    db.query(
        `CALL SP_INSERT_VENTAS(
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?
        )`,
        [
            datos.accion,

            datos.cod_categoria || null,
            datos.cod_ciclo_evento || null,
            datos.cod_reservacion || null,
            datos.cod_evento || null,
            datos.cod_boleto || null,
            datos.cod_cliente || null,
            datos.cod_venta || null,

            datos.nom_categoria || null,
            datos.des_categoria || null,

            datos.nom_ciclo || null,
            datos.des_ciclo || null,
            datos.usr_ingreso || null,

            datos.nom_evento || null,
            datos.des_evento || null,
            datos.fec_evento || null,
            datos.hor_evento || null,
            datos.des_lugar || null,
            datos.num_capacidad || null,
            datos.ind_estado || null,

            datos.tip_boleto || null,
            datos.mon_precio || null,
            datos.num_disponible || null,
            datos.des_boleto || null,

            datos.mon_total || null,
            datos.metodo_pago || null,
            datos.estado_venta || null,

            datos.cantidad || null,
            datos.precio_unit || null,
            datos.subtotal || null,

            datos.mon_pago || null,
            datos.referencia || null,
            datos.estado_pago || null
        ],
        callback
    );
};

const actualizar = (datos, callback) => {

    db.query(
        `CALL SP_UPDATE_VENTAS(
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?
        )`,
        [
            datos.accion,

            datos.cod_categoria || null,
            datos.nom_categoria || null,
            datos.des_categoria || null,

            datos.cod_ciclo_evento || null,
            datos.nom_ciclo || null,
            datos.des_ciclo || null,
            datos.ind_activo_ciclo || null,

            datos.cod_evento || null,
            datos.cod_reservacion || null,
            datos.nom_evento || null,
            datos.des_evento || null,
            datos.fec_evento || null,
            datos.hor_evento || null,
            datos.des_lugar || null,
            datos.num_capacidad || null,
            datos.ind_estado_evento || null,

            datos.cod_boleto || null,
            datos.tip_boleto || null,
            datos.mon_precio || null,
            datos.num_disponible || null,
            datos.des_boleto || null,

            datos.cod_venta || null,
            datos.cod_cliente || null,
            datos.mon_total || null,
            datos.ind_metodo_pago || null,
            datos.ind_estado_venta || null,

            datos.cod_detalle || null,
            datos.num_cantidad || null,
            datos.mon_precio_unit || null,
            datos.mon_subtotal || null,

            datos.cod_pago || null,
            datos.mon_pago || null,
            datos.cod_referencia || null,
            datos.ind_estado_pago || null,

            datos.ind_activo || '1'
        ],
        callback
    );
};

module.exports = {
    obtener,
    insertar,
    actualizar
};
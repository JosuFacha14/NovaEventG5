const db = require('../config/db');


// ==========================================
// RP_TABLA_REPORTES
// ==========================================

function getReportes(cod_reporte, callback) {
    db.query(
        'CALL SP_RP_SELECT(?, ?, null, null, null, null)',
        ['SEL_RP_TABLA_REPORTES', cod_reporte || null],
        (err, rows) => {
            if (err) {
                console.error("Error en getReportes:", err);
                return callback(err, null);
            }
            callback(null, rows[0] || []);
        }
    );
}

function createReporte({ pv_tip_reporte, pd_fec_periodo_desde, pd_fec_periodo_hasta, pv_obs_reporte, pv_usr_registro }, callback) {
    if (!pv_tip_reporte || pv_tip_reporte.trim() === '') {
        return callback(new Error('El tipo de reporte es obligatorio'), null);
    }

    db.query(
        `CALL SP_RP_INSERT(
            'INS_RP_TABLA_REPORTES', ?, NOW(), ?, ?, ?, ?,
            null, null, null, null, null,
            null, null, null, null,
            null, null, null, null, null, null, null
        )`,
        [
            pv_tip_reporte.trim(),
            pd_fec_periodo_desde || null,
            pd_fec_periodo_hasta || null,
            pv_obs_reporte || null,
            pv_usr_registro || 'Sistema'
        ],
        (err, result) => {
            if (err) {
                console.error("Error en createReporte:", err);
                return callback(err, null);
            }

            db.query('SELECT LAST_INSERT_ID() as id', (err, rows) => {
                if (err) {
                    console.error("Error obteniendo LAST_INSERT_ID:", err);
                    return callback(err, null);
                }
                const insertId = rows[0].id;

                db.query(
                    'CALL SP_RP_SELECT(?, ?, null, null, null, null)',
                    ['SEL_RP_TABLA_REPORTES', insertId],
                    (err, nuevoReporte) => {
                        if (err) {
                            console.error("Error obteniendo nuevo reporte:", err);
                            return callback(err, null);
                        }
                        callback(null, nuevoReporte[0]?.[0] || null);
                    }
                );
            });
        }
    );
}

function updateReporte(id, { pv_tip_reporte, pd_fec_periodo_desde, pd_fec_periodo_hasta, pv_obs_reporte }, callback) {
    if (!id || isNaN(id) || parseInt(id) <= 0) {
        return callback(new Error('ID de reporte invalido'), null);
    }

    if (!pv_tip_reporte || pv_tip_reporte.trim() === '') {
        return callback(new Error('El tipo de reporte es obligatorio'), null);
    }

    db.query(
        `CALL SP_RP_UPDATE(
            'UPD_RP_TABLA_REPORTES', ?, ?, NOW(), ?, ?, ?,
            null, null, null, null, null, null,
            null, null, null, null, null,
            null, null, null, null, null, null, null
        )`,
        [
            parseInt(id),
            pv_tip_reporte.trim(),
            pd_fec_periodo_desde || null,
            pd_fec_periodo_hasta || null,
            pv_obs_reporte || null
        ],
        (err, result) => {
            if (err) {
                console.error("Error en updateReporte:", err);
                return callback(err, null);
            }
            callback(null, result);
        }
    );
}

// ==========================================
// RP_GANANCIAS
// ==========================================

function getGanancias(callback) {
    db.query(
        'CALL SP_RP_SELECT(?, null, null, null, null, null)',
        ['SEL_RP_GANANCIAS'],
        (err, rows) => {
            if (err) {
                console.error("Error en getGanancias:", err);
                return callback(err, null);
            }
            callback(null, rows[0] || []);
        }
    );
}

function getGananciaById(id, callback) {
    if (!id || isNaN(id) || parseInt(id) <= 0) {
        return callback(new Error('ID de ganancia invalido'), null);
    }

    db.query(
        'CALL SP_RP_SELECT(?, null, ?, null, null, null)',
        ['SEL_RP_GANANCIAS', parseInt(id)],
        (err, rows) => {
            if (err) {
                console.error("Error en getGananciaById:", err);
                return callback(err, null);
            }
            callback(null, rows[0]?.[0] || null);
        }
    );
}

function createGanancia({ cod_evento, mon_ingresos, mon_costos, mon_utilidad, fec_cierre, usr_registro }, callback) {
    if (!cod_evento || isNaN(cod_evento) || parseInt(cod_evento) <= 0) {
        return callback(new Error('El codigo del evento es obligatorio'), null);
    }

    db.query(
        `CALL SP_RP_INSERT(
            'INS_RP_GANANCIAS',
            null, null, null, null, null, ?,
            ?, ?, ?, ?, ?,
            null, null, null, null,
            null, null, null, null, null, null, null
        )`,
        [
            usr_registro || 'Sistema',
            parseInt(cod_evento),
            mon_ingresos || 0,
            mon_costos || 0,
            mon_utilidad || 0,
            fec_cierre || null
        ],
        (err, result) => {
            if (err) {
                console.error("Error en createGanancia:", err);
                return callback(err, null);
            }

            db.query('SELECT LAST_INSERT_ID() as id', (err, rows) => {
                if (err) {
                    console.error("Error obteniendo LAST_INSERT_ID:", err);
                    return callback(err, null);
                }
                const insertId = rows[0].id;
                getGananciaById(insertId, callback);
            });
        }
    );
}

function updateGanancia(id, { cod_evento, mon_ingresos, mon_costos, mon_utilidad, fec_cierre }, callback) {
    if (!id || isNaN(id) || parseInt(id) <= 0) {
        return callback(new Error('ID de ganancia invalido'), null);
    }

    db.query(
        `CALL SP_RP_UPDATE(
            'UPD_RP_GANANCIAS',
            null, null, null, null, null, null,
            ?, ?, ?, ?, ?, ?,
            null, null, null, null, null,
            null, null, null, null, null, null, null
        )`,
        [
            parseInt(id),
            parseInt(cod_evento),
            mon_ingresos || 0,
            mon_costos || 0,
            mon_utilidad || 0,
            fec_cierre || null
        ],
        (err, result) => {
            if (err) {
                console.error("Error en updateGanancia:", err);
                return callback(err, null);
            }
            callback(null, result);
        }
    );
}

// ==========================================
// RP_REPORTE_INVENTARIO
// ==========================================

function getReportesInventario(callback) {
    db.query(
        'CALL SP_RP_SELECT(?, null, null, null, null, null)',
        ['SEL_RP_REPORTE_INVENTARIO'],
        (err, rows) => {
            if (err) {
                console.error("Error en getReportesInventario:", err);
                return callback(err, null);
            }
            callback(null, rows[0] || []);
        }
    );
}

function getReporteInventarioById(id, callback) {
    if (!id || isNaN(id) || parseInt(id) <= 0) {
        return callback(new Error('ID de reporte inventario invalido'), null);
    }

    db.query(
        'CALL SP_RP_SELECT(?, null, null, ?, null, null)',
        ['SEL_RP_REPORTE_INVENTARIO', parseInt(id)],
        (err, rows) => {
            if (err) {
                console.error("Error en getReporteInventarioById:", err);
                return callback(err, null);
            }
            callback(null, rows[0]?.[0] || null);
        }
    );
}

function createReporteInventario({ cod_item, cod_evento, can_utilizada, des_estado_final, obs_notas, usr_registro }, callback) {
    if (!cod_item || isNaN(cod_item) || parseInt(cod_item) <= 0) {
        return callback(new Error('El codigo del item es obligatorio'), null);
    }
    if (!cod_evento || isNaN(cod_evento) || parseInt(cod_evento) <= 0) {
        return callback(new Error('El codigo del evento es obligatorio'), null);
    }

    db.query(
        `CALL SP_RP_INSERT(
            'INS_RP_REPORTE_INVENTARIO',
            null, null, null, null, null, ?,
            ?, null, null, null, null,
            ?, ?, ?, ?,
            null, null, null, null, null, null, null
        )`,
        [
            usr_registro || 'Sistema',      
            parseInt(cod_evento),           
            parseInt(cod_item),             
            parseInt(can_utilizada) || 0,   
            des_estado_final || null,       
            obs_notas || null               
        ],
        (err, result) => {
            if (err) {
                console.error("Error en createReporteInventario:", err);
                return callback(err, null);
            }

            db.query('SELECT LAST_INSERT_ID() as id', (err, rows) => {
                if (err) {
                    console.error("Error obteniendo LAST_INSERT_ID:", err);
                    return callback(err, null);
                }
                const insertId = rows[0].id;
                getReporteInventarioById(insertId, callback);
            });
        }
    );
}

function updateReporteInventario(id, { cod_item, cod_evento, can_utilizada, des_estado_final, obs_notas }, callback) {
    if (!id || isNaN(id) || parseInt(id) <= 0) {
        return callback(new Error('ID de reporte inventario invalido'), null);
    }
    if (!cod_evento || isNaN(cod_evento) || parseInt(cod_evento) <= 0) {
        return callback(new Error('El codigo del evento es obligatorio'), null);
    }

    db.query(
        
        `CALL SP_RP_UPDATE(
            'UPD_RP_REPORTE_INVENTARIO',
            null, null, null, null, null, null,
            null, ?, null, null, null, null,
            ?, ?, ?, ?, ?,
            null, null, null, null, null, null, null
        )`,
        [
            parseInt(cod_evento),          
            parseInt(id),                  
            parseInt(cod_item),            
            can_utilizada || 0,            
            des_estado_final || null,      
            obs_notas || null              
        ],
        (err, result) => {
            if (err) {
                console.error("Error en updateReporteInventario:", err);
                return callback(err, null);
            }
            callback(null, result);
        }
    );
}

// ==========================================
// RP_COSTOS_OPERATIVOS
// ==========================================

function getCostosOperativos(callback) {
    db.query(
        'CALL SP_RP_SELECT(?, null, null, null, null, null)',
        ['SEL_RP_COSTOS_OPERATIVOS'],
        (err, rows) => {
            if (err) {
                console.error("Error en getCostosOperativos:", err);
                return callback(err, null);
            }
            callback(null, rows[0] || []);
        }
    );
}

function getCostoOperativoById(id, callback) {
    if (!id || isNaN(id) || parseInt(id) <= 0) {
        return callback(new Error('ID de costo operativo invalido'), null);
    }

    db.query(
        'CALL SP_RP_SELECT(?, null, null, null, ?, null)',
        ['SEL_RP_COSTOS_OPERATIVOS', parseInt(id)],
        (err, rows) => {
            if (err) {
                console.error("Error en getCostoOperativoById:", err);
                return callback(err, null);
            }
            callback(null, rows[0]?.[0] || null);
        }
    );
}

function createCostoOperativo({ cod_evento, cod_reporte, cod_proveedor, ind_categoria, des_costo, mon_presupuestado, mon_real, obs_costo, usr_registro }, callback) {
    if (!cod_evento || isNaN(cod_evento) || parseInt(cod_evento) <= 0) {
        return callback(new Error('El codigo del evento es obligatorio'), null);
    }

    db.query(
        `CALL SP_RP_INSERT(
            'INS_RP_COSTOS_OPERATIVOS',
            null, null, null, null, null, ?,
            ?, null, null, null, null,
            null, null, null, null,
            ?, ?, ?, ?, ?, ?, ?
        )`,
        [
            usr_registro || 'Sistema',       
            parseInt(cod_evento),            
            cod_reporte || null,             
            cod_proveedor || null,           
            ind_categoria || null,           
            des_costo || null,               
            mon_presupuestado || 0,          
            mon_real || 0,                   
            obs_costo || null                
        ],
        (err, result) => {
            if (err) {
                console.error("Error en createCostoOperativo:", err);
                return callback(err, null);
            }

            db.query('SELECT LAST_INSERT_ID() as id', (err, rows) => {
                if (err) {
                    console.error("Error obteniendo LAST_INSERT_ID:", err);
                    return callback(err, null);
                }
                const insertId = rows[0].id;
                getCostoOperativoById(insertId, callback);
            });
        }
    );
}

function updateCostoOperativo(id, { cod_evento, cod_reporte, cod_proveedor, ind_categoria, des_costo, mon_presupuestado, mon_real, obs_costo }, callback) {
    if (!id || isNaN(id) || parseInt(id) <= 0) {
        return callback(new Error('ID de costo operativo invalido'), null);
    }
    if (!cod_evento || isNaN(cod_evento) || parseInt(cod_evento) <= 0) {
        return callback(new Error('El codigo del evento es obligatorio'), null);
    }

    db.query(
        
        `CALL SP_RP_UPDATE(
            'UPD_RP_COSTOS_OPERATIVOS',
            ?, null, null, null, null, null,
            null, ?, null, null, null, null,
            null, null, null, null, null,
            ?, ?, ?, ?, ?, ?, ?
        )`,
        [
            cod_reporte || null,           
            parseInt(cod_evento),          
            parseInt(id),                  
            cod_proveedor || null,         
            ind_categoria || null,         
            des_costo || null,             
            mon_presupuestado || 0,        
            mon_real || 0,                 
            obs_costo || null              
        ],
        (err, result) => {
            if (err) {
                console.error("Error en updateCostoOperativo:", err);
                return callback(err, null);
            }
            callback(null, result);
        }
    );
}

module.exports = {
    // RP_TABLA_REPORTES
    getReportes,
    createReporte,
    updateReporte,
    // RP_GANANCIAS
    getGanancias,
    getGananciaById,
    createGanancia,
    updateGanancia,
    // RP_REPORTE_INVENTARIO
    getReportesInventario,
    getReporteInventarioById,
    createReporteInventario,
    updateReporteInventario,
    // RP_COSTOS_OPERATIVOS
    getCostosOperativos,
    getCostoOperativoById,
    createCostoOperativo,
    updateCostoOperativo
};
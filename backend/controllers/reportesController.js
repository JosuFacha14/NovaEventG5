const reportesModel = require('../models/reportesModel');

// ==========================================
// RP_TABLA_REPORTES
// ==========================================

function getReportes(req, res) {
    const { cod_reporte } = req.query;

    if (cod_reporte && (isNaN(cod_reporte) || parseInt(cod_reporte) <= 0)) {
        return res.status(400).json({ 
            ok: false, 
            msg: 'El codigo de reporte debe ser un numero valido' 
        });
    }

    reportesModel.getReportes(cod_reporte, (err, data) => {
        if (err) {
            console.error("Error en GET reportes:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al obtener reportes' 
            });
        }
        res.status(200).json(data);
    });
}

function createReporte(req, res) {
    const { pv_tip_reporte, pd_fec_periodo_desde, pd_fec_periodo_hasta, pv_obs_reporte, pv_usr_registro } = req.body;

    if (!pv_tip_reporte || pv_tip_reporte.trim() === '') {
        return res.status(400).json({ 
            ok: false, 
            msg: 'El tipo de reporte es obligatorio y no puede estar vacio' 
        });
    }

    if (pd_fec_periodo_desde && pd_fec_periodo_hasta) {
        const fechaDesde = new Date(pd_fec_periodo_desde);
        const fechaHasta = new Date(pd_fec_periodo_hasta);
        
        if (fechaDesde > fechaHasta) {
            return res.status(400).json({
                ok: false,
                msg: 'La fecha de inicio no puede ser mayor que la fecha de fin'
            });
        }
    }

    reportesModel.createReporte({
        pv_tip_reporte: pv_tip_reporte.trim(),
        pd_fec_periodo_desde,
        pd_fec_periodo_hasta,
        pv_obs_reporte: pv_obs_reporte ? pv_obs_reporte.trim() : null,
        pv_usr_registro: pv_usr_registro ? pv_usr_registro.trim() : 'Sistema'
    }, (err, resultado) => {
        if (err) {
            console.error("Error en POST reporte:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al guardar el reporte' 
            });
        }
        res.status(201).json(resultado);
    });
}

function updateReporte(req, res) {
    const { id } = req.params;
    const { pv_tip_reporte, pd_fec_periodo_desde, pd_fec_periodo_hasta, pv_obs_reporte } = req.body;

    if (!id || isNaN(id) || parseInt(id) <= 0) {
        return res.status(400).json({ 
            ok: false, 
            msg: 'ID de reporte invalido' 
        });
    }

    if (!pv_tip_reporte || pv_tip_reporte.trim() === '') {
        return res.status(400).json({ 
            ok: false, 
            msg: 'El tipo de reporte es obligatorio y no puede estar vacio' 
        });
    }

    if (pd_fec_periodo_desde && pd_fec_periodo_hasta) {
        const fechaDesde = new Date(pd_fec_periodo_desde);
        const fechaHasta = new Date(pd_fec_periodo_hasta);
        
        if (fechaDesde > fechaHasta) {
            return res.status(400).json({
                ok: false,
                msg: 'La fecha de inicio no puede ser mayor que la fecha de fin'
            });
        }
    }

    reportesModel.updateReporte(id, {
        pv_tip_reporte: pv_tip_reporte.trim(),
        pd_fec_periodo_desde,
        pd_fec_periodo_hasta,
        pv_obs_reporte: pv_obs_reporte ? pv_obs_reporte.trim() : null
    }, (err) => {
        if (err) {
            console.error("Error en PUT reporte:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al actualizar reporte' 
            });
        }

        reportesModel.getReportes(id, (err, data) => {
            if (err) {
                console.error("Error obteniendo reporte actualizado:", err);
                return res.status(500).json({ 
                    ok: false, 
                    msg: 'Error al obtener el reporte actualizado' 
                });
            }
            res.status(200).json(data[0] || null);
        });
    });
}

// ==========================================
// RP_GANANCIAS
// ==========================================

function getGanancias(req, res) {
    reportesModel.getGanancias((err, data) => {
        if (err) {
            console.error("Error en GET ganancias:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al obtener ganancias' 
            });
        }
        res.status(200).json(data);
    });
}

function getGananciaById(req, res) {
    const { id } = req.params;

    reportesModel.getGananciaById(id, (err, data) => {
        if (err) {
            console.error("Error en GET ganancia by ID:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al obtener la ganancia' 
            });
        }
        if (!data) {
            return res.status(404).json({ 
                ok: false, 
                msg: 'Ganancia no encontrada' 
            });
        }
        res.status(200).json(data);
    });
}

function createGanancia(req, res) {
    const { cod_evento, mon_ingresos, mon_costos, mon_utilidad, fec_cierre, usr_registro } = req.body;

    if (!cod_evento || isNaN(cod_evento) || parseInt(cod_evento) <= 0) {
        return res.status(400).json({ 
            ok: false, 
            msg: 'El codigo del evento es obligatorio' 
        });
    }

    reportesModel.createGanancia({
        cod_evento,
        mon_ingresos,
        mon_costos,
        mon_utilidad,
        fec_cierre,
        usr_registro
    }, (err, resultado) => {
        if (err) {
            console.error("Error en POST ganancia:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al guardar la ganancia' 
            });
        }
        res.status(201).json(resultado);
    });
}

function updateGanancia(req, res) {
    const { id } = req.params;
    const { cod_evento, mon_ingresos, mon_costos, mon_utilidad, fec_cierre } = req.body;

    reportesModel.updateGanancia(id, {
        cod_evento,
        mon_ingresos,
        mon_costos,
        mon_utilidad,
        fec_cierre
    }, (err) => {
        if (err) {
            console.error("Error en PUT ganancia:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al actualizar la ganancia' 
            });
        }

        reportesModel.getGananciaById(id, (err, data) => {
            if (err) {
                console.error("Error obteniendo ganancia actualizada:", err);
                return res.status(500).json({ 
                    ok: false, 
                    msg: 'Error al obtener la ganancia actualizada' 
                });
            }
            res.status(200).json(data);
        });
    });
}

// ==========================================
// RP_REPORTE_INVENTARIO
// ==========================================

function getReportesInventario(req, res) {
    reportesModel.getReportesInventario((err, data) => {
        if (err) {
            console.error("Error en GET reportes inventario:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al obtener reportes de inventario' 
            });
        }
        res.status(200).json(data);
    });
}

function getReporteInventarioById(req, res) {
    const { id } = req.params;

    reportesModel.getReporteInventarioById(id, (err, data) => {
        if (err) {
            console.error("Error en GET reporte inventario by ID:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al obtener el reporte de inventario' 
            });
        }
        if (!data) {
            return res.status(404).json({ 
                ok: false, 
                msg: 'Reporte de inventario no encontrado' 
            });
        }
        res.status(200).json(data);
    });
}

function createReporteInventario(req, res) {
    const { cod_item, cod_evento, can_utilizada, des_estado_final, obs_notas, usr_registro } = req.body;

    if (!cod_item || isNaN(cod_item) || parseInt(cod_item) <= 0) {
        return res.status(400).json({ 
            ok: false, 
            msg: 'El codigo del item es obligatorio' 
        });
    }
    if (!cod_evento || isNaN(cod_evento) || parseInt(cod_evento) <= 0) {
        return res.status(400).json({ 
            ok: false, 
            msg: 'El codigo del evento es obligatorio' 
        });
    }

    reportesModel.createReporteInventario({
        cod_item,
        cod_evento,
        can_utilizada,
        des_estado_final,
        obs_notas,
        usr_registro
    }, (err, resultado) => {
        if (err) {
            console.error("Error en POST reporte inventario:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al guardar el reporte de inventario' 
            });
        }
        res.status(201).json(resultado);
    });
}

function updateReporteInventario(req, res) {
    const { id } = req.params;
    const { cod_item, cod_evento, can_utilizada, des_estado_final, obs_notas } = req.body;

    reportesModel.updateReporteInventario(id, {
        cod_item,
        cod_evento,
        can_utilizada,
        des_estado_final,
        obs_notas
    }, (err) => {
        if (err) {
            console.error("Error en PUT reporte inventario:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al actualizar el reporte de inventario' 
            });
        }

        reportesModel.getReporteInventarioById(id, (err, data) => {
            if (err) {
                console.error("Error obteniendo reporte inventario actualizado:", err);
                return res.status(500).json({ 
                    ok: false, 
                    msg: 'Error al obtener el reporte de inventario actualizado' 
                });
            }
            res.status(200).json(data);
        });
    });
}

// ==========================================
// RP_COSTOS_OPERATIVOS
// ==========================================

function getCostosOperativos(req, res) {
    reportesModel.getCostosOperativos((err, data) => {
        if (err) {
            console.error("Error en GET costos operativos:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al obtener costos operativos' 
            });
        }
        res.status(200).json(data);
    });
}

function getCostoOperativoById(req, res) {
    const { id } = req.params;

    reportesModel.getCostoOperativoById(id, (err, data) => {
        if (err) {
            console.error("Error en GET costo operativo by ID:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al obtener el costo operativo' 
            });
        }
        if (!data) {
            return res.status(404).json({ 
                ok: false, 
                msg: 'Costo operativo no encontrado' 
            });
        }
        res.status(200).json(data);
    });
}

function createCostoOperativo(req, res) {
    const { cod_evento, cod_reporte, cod_proveedor, ind_categoria, des_costo, mon_presupuestado, mon_real, obs_costo, usr_registro } = req.body;

    if (!cod_evento || isNaN(cod_evento) || parseInt(cod_evento) <= 0) {
        return res.status(400).json({ 
            ok: false, 
            msg: 'El codigo del evento es obligatorio' 
        });
    }

    reportesModel.createCostoOperativo({
        cod_evento,
        cod_reporte,
        cod_proveedor,
        ind_categoria,
        des_costo,
        mon_presupuestado,
        mon_real,
        obs_costo,
        usr_registro
    }, (err, resultado) => {
        if (err) {
            console.error("Error en POST costo operativo:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al guardar el costo operativo' 
            });
        }
        res.status(201).json(resultado);
    });
}

function updateCostoOperativo(req, res) {
    const { id } = req.params;
    const { cod_evento, cod_reporte, cod_proveedor, ind_categoria, des_costo, mon_presupuestado, mon_real, obs_costo } = req.body;

    reportesModel.updateCostoOperativo(id, {
        cod_evento,
        cod_reporte,
        cod_proveedor,
        ind_categoria,
        des_costo,
        mon_presupuestado,
        mon_real,
        obs_costo
    }, (err) => {
        if (err) {
            console.error("Error en PUT costo operativo:", err);
            return res.status(500).json({ 
                ok: false, 
                msg: err.message || 'Error al actualizar el costo operativo' 
            });
        }

        reportesModel.getCostoOperativoById(id, (err, data) => {
            if (err) {
                console.error("Error obteniendo costo operativo actualizado:", err);
                return res.status(500).json({ 
                    ok: false, 
                    msg: 'Error al obtener el costo operativo actualizado' 
                });
            }
            res.status(200).json(data);
        });
    });
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
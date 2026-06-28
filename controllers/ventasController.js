const ventasModel = require('../models/ventasModel');

exports.obtener = (req, res) => {

    const { accion } = req.query;

    ventasModel.obtener(
        accion,
        (error, result) => {

            if (error) {
                return res.status(500).json(error);
            }

            res.json(result[0]);
        }
    );
};

exports.insertar = (req, res) => {

    ventasModel.insertar(
        req.body,
        (error, result) => {

            if (error) {
                return res.status(500).json(error);
            }

            res.json({
                mensaje: 'Registro insertado correctamente'
            });
        }
    );
};

exports.actualizar = (req, res) => {

    ventasModel.actualizar(
        req.body,
        (error, result) => {

            if (error) {
                return res.status(500).json(error);
            }

            res.json({
                mensaje: 'Registro actualizado correctamente'
            });
        }
    );
};

/* ==========================
   PATCH -> SOFT DELETE
========================== */

exports.eliminar = (req, res) => {

    ventasModel.actualizar(
        req.body,
        (error, result) => {

            if (error) {
                return res.status(500).json(error);
            }

            res.json({
                mensaje: 'Registro eliminado correctamente'
            });
        }
    );
};
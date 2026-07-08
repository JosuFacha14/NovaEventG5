const ventasModel = require('../models/ventasModel');

/* ==========================
   GET
========================== */

exports.obtener = (req, res) => {

    const { accion } = req.query;

    ventasModel.obtener(
        accion,
        (error, result) => {

            if (error) {
                console.log(error);
                return res.status(500).json(error);
            }

            res.json(result[0]);
        }
    );
};

/* ==========================
   POST
========================== */

exports.insertar = (req, res) => {

    ventasModel.insertar(
        req.body,
        (error, result) => {

            if (error) {
                console.log(error);
                return res.status(500).json(error);
            }

            res.json({
                mensaje: 'Registro insertado correctamente'
            });
        }
    );
};

/* ==========================
   PUT -> UPDATE / SOFT DELETE
========================== */

exports.actualizar = (req, res) => {

    req.body.id = req.params.id;

    ventasModel.actualizar(
        req.body,
        (error, result) => {

            if (error) {
                console.log(error);
                return res.status(500).json(error);
            }

            if (req.body.accion === 'SOFT_DELETE') {

                return res.json({
                    mensaje: 'Registro eliminado correctamente'
                });

            }

            res.json({
                mensaje: 'Registro actualizado correctamente'
            });

        }
    );
};
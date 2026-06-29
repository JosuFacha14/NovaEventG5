const personasModel = require('../models/personasModel');

/* ==========================
   GET
========================== */

exports.obtener = (req, res) => {

    personasModel.obtener(
        req.query,
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

    personasModel.insertar(
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
   PUT (UPDATE / SOFT DELETE)
========================== */

exports.actualizar = (req, res) => {

    // Si el id viene por la URL y no en el body, lo asignamos.
    if (req.params.id && !req.body.cod_persona) {
        req.body.cod_persona = req.params.id;
    }

    personasModel.actualizar(
        req.body,
        (error, result) => {

            if (error) {
                console.log(error);
                return res.status(500).json(error);
            }

            if (req.body.accion === 'SOFT_DELETE') {

                return res.json({
                    mensaje: 'Registro desactivado correctamente'
                });

            }

            res.json({
                mensaje: 'Registro actualizado correctamente'
            });

        }
    );
};
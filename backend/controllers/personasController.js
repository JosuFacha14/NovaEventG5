const personasModel = require('../models/personasModel');
 
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
 
exports.insertar = (req, res) => {
 
    personasModel.insertar(
        req.body,
        (error, result) => {
 
            if (error) {
                console.log(error);
                return res.status(500).json(error);
            }
 
            res.json({
                mensaje: 'Registro insertado correctamente',
                NUEVO_ID: result.nuevoId
            });
        }
    );
};
 
exports.actualizar = (req, res) => {
 
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
 
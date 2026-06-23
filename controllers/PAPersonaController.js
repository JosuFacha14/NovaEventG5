const personaModel = require('../models/PAPersonaModel');

// PUT /api/personas/:id  →  Actualizar persona
async function update(req, res) {
  try {
    const codPersona = req.params.id;

    // Verificar que la persona existe
    const existe = await personaModel.getById(codPersona);
    if (!existe) {
      return res.status(404).json({
        success: false,
        message: 'Persona no encontrada'
      });
    }

    // Validar que venga al menos USR_INGRESO
    if (!req.body.USR_INGRESO) {
      return res.status(400).json({
        success: false,
        message: 'USR_INGRESO es obligatorio'
      });
    }

    const actualizado = await personaModel.update(codPersona, req.body);
    res.json({ success: true, data: actualizado });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

// PUT /api/personas/:id/desactivar  →  Soft delete (desactivar usuario)
async function softDelete(req, res) {
  try {
    const codPersona = req.params.id;

    // Verificar que la persona existe
    const existe = await personaModel.getById(codPersona);
    if (!existe) {
      return res.status(404).json({
        success: false,
        message: 'Persona no encontrada'
      });
    }

    if (!req.body.USR_INGRESO) {
      return res.status(400).json({
        success: false,
        message: 'USR_INGRESO es obligatorio'
      });
    }

    const resultado = await personaModel.softDelete(codPersona, req.body.USR_INGRESO);
    res.json({ success: true, data: resultado, message: 'Usuario desactivado correctamente' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

module.exports = { update, softDelete };

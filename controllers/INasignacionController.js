const asignacionModel = require('../models/INasignacionModel');

async function create(req, res) {
  try {
    const { COD_EVENTO, COD_ITEM, CAN_ASIGNADA, FEC_SALIDA, USR_REGISTRO } = req.body;
    if (!COD_EVENTO || !COD_ITEM || !CAN_ASIGNADA || !FEC_SALIDA || !USR_REGISTRO) {
      return res.status(400).json({
        success: false,
        message: 'COD_EVENTO, COD_ITEM, CAN_ASIGNADA, FEC_SALIDA y USR_REGISTRO son obligatorios'
      });
    }
    const nuevo = await asignacionModel.create(req.body);
    res.status(201).json({ success: true, data: nuevo });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

async function update(req, res) {
  try {
    const existe = await asignacionModel.getById(req.params.id);
    if (!existe) return res.status(404).json({ success: false, message: 'Asignacion no encontrada' });

    const actualizado = await asignacionModel.update(req.params.id, req.body);
    res.json({ success: true, data: actualizado });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

module.exports = { create, update };
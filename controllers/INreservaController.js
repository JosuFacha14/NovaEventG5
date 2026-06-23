const reservaModel = require('../models/INreservaModel');

async function create(req, res) {
  try {
    const { COD_EVENTO, COD_ITEM, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA, USR_REGISTRO } = req.body;
    if (!COD_EVENTO || !COD_ITEM || !CAN_RESERVADA || !FEC_INICIO_RESERVA || !FEC_FIN_RESERVA || !USR_REGISTRO) {
      return res.status(400).json({
        success: false,
        message: 'COD_EVENTO, COD_ITEM, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA y USR_REGISTRO son obligatorios'
      });
    }
    const nuevo = await reservaModel.create(req.body);
    res.status(201).json({ success: true, data: nuevo });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

async function update(req, res) {
  try {
    const existe = await reservaModel.getById(req.params.id);
    if (!existe) return res.status(404).json({ success: false, message: 'Reserva no encontrada' });

    const actualizado = await reservaModel.update(req.params.id, req.body);
    res.json({ success: true, data: actualizado });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

module.exports = { create, update };
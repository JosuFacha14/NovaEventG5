// modelo del modulo de reservacion
const model = require('../models/reservacionModel');

// inserts

// insertar espacio
const insEspacio = (req, res) => {
  model.insEspacio(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'espacio creado', resultado: result });
  });
};

// insertar reservacion
const insReservacion = (req, res) => {
  model.insReservacion(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'reservacion creada', resultado: result });
  });
};

// insertar espacio ocupado
const insEspacioOcupado = (req, res) => {
  model.insEspacioOcupado(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'espacio ocupado registrado', resultado: result });
  });
};

// insertar historial de reservacion
const insHistorialReservacion = (req, res) => {
  model.insHistorialReservacion(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'historial registrado', resultado: result });
  });
};

// updates

// actualizar espacio
const updEspacio = (req, res) => {
  const datos = { ...req.body, cod_espacio: req.params.id };
  model.updEspacio(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'espacio actualizado', resultado: result });
  });
};

// actualizar reservacion
const updReservacion = (req, res) => {
  const datos = { ...req.body, cod_reservacion: req.params.id };
  model.updReservacion(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'reservacion actualizada', resultado: result });
  });
};

// actualizar espacio ocupado
const updEspacioOcupado = (req, res) => {
  const datos = { ...req.body, cod_espa_ocup: req.params.id };
  model.updEspacioOcupado(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'espacio ocupado actualizado', resultado: result });
  });
};

// soft delete espacio
const softDeleteEspacio = (req, res) => {
  const datos = { cod_espacio: req.params.id, ind_estado: req.body.ind_estado };
  model.softDeleteEspacio(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'estado del espacio actualizado', resultado: result });
  });
};

// soft delete reservacion
const softDeleteReservacion = (req, res) => {
  const datos = { cod_reservacion: req.params.id, ind_estado: req.body.ind_estado };
  model.softDeleteReservacion(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'estado de la reservacion actualizado', resultado: result });
  });
};

// selects

// obtener espacios
const selEspacio = (req, res) => {
  model.selEspacio(req.params.id || null, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[0]);
  });
};

// obtener reservaciones
const selReservacion = (req, res) => {
  model.selReservacion(req.params.id || null, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[0]);
  });
};

// obtener espacios ocupados
const selEspacioOcupado = (req, res) => {
  model.selEspacioOcupado(req.params.id || null, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[0]);
  });
};

// obtener historial de reservacion
const selHistorialReservacion = (req, res) => {
  model.selHistorialReservacion(req.params.cod_reservacion || null, req.params.id || null, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[0]);
  });
};

module.exports = {
  insEspacio,
  insReservacion,
  insEspacioOcupado,
  insHistorialReservacion,
  updEspacio,
  updReservacion,
  updEspacioOcupado,
  softDeleteEspacio,
  softDeleteReservacion,
  selEspacio,
  selReservacion,
  selEspacioOcupado,
  selHistorialReservacion
};
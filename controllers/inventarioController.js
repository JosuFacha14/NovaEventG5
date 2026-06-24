// modelo del modulo de inventario
const model = require('../models/inventarioModel');

/* ==========================
   ALMACENES
========================== */

const insAlmacen = (req, res) => {
  model.insAlmacen(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'almacen creado', resultado: result });
  });
};

const updAlmacen = (req, res) => {
  const datos = { ...req.body, cod_almacen: req.params.id };
  model.updAlmacen(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'almacen actualizado', resultado: result });
  });
};

/* ==========================
   CATEGORIAS
========================== */

const insCategoria = (req, res) => {
  model.insCategoria(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'categoria creada', resultado: result });
  });
};

const updCategoria = (req, res) => {
  const datos = { ...req.body, cod_categoria: req.params.id };
  model.updCategoria(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'categoria actualizada', resultado: result });
  });
};

/* ==========================
   ITEMS
========================== */

const insItem = (req, res) => {
  model.insItem(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'item creado', resultado: result });
  });
};

const updItem = (req, res) => {
  const datos = { ...req.body, cod_item: req.params.id };
  model.updItem(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'item actualizado', resultado: result });
  });
};

/* ==========================
   ASIGNACIONES
========================== */

const insAsignacion = (req, res) => {
  model.insAsignacion(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'asignacion creada', resultado: result });
  });
};

const updAsignacion = (req, res) => {
  const datos = { ...req.body, cod_asignacion: req.params.id };
  model.updAsignacion(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'asignacion actualizada', resultado: result });
  });
};

/* ==========================
   RESERVAS
========================== */

const insReserva = (req, res) => {
  model.insReserva(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'reserva de inventario creada', resultado: result });
  });
};

const updReserva = (req, res) => {
  const datos = { ...req.body, cod_reserva: req.params.id };
  model.updReserva(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'reserva de inventario actualizada', resultado: result });
  });
};

module.exports = {
  insAlmacen, updAlmacen,
  insCategoria, updCategoria,
  insItem, updItem,
  insAsignacion, updAsignacion,
  insReserva, updReserva
};

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-08-2023 a las 00:26:31
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `negocios_v`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `ID` int(11) NOT NULL,
  `cuenta` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`ID`, `cuenta`) VALUES
(1, 'Examen'),
(2, 'prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen`
--

CREATE TABLE `examen` (
  `ID` int(11) NOT NULL,
  `NombreEsquema` varchar(50) DEFAULT NULL,
  `TipoCuenta` varchar(50) DEFAULT NULL,
  `Montos` int(11) DEFAULT NULL,
  `Cuenta` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examen`
--

INSERT INTO `examen` (`ID`, `NombreEsquema`, `TipoCuenta`, `Montos`, `Cuenta`) VALUES
(1, 'Bancos', 'Activo', 6, 'Examen'),
(2, 'Equipo de Oficina', 'Activo', 2, 'Examen'),
(3, 'Almacen', 'Activo', 4, 'Examen'),
(4, 'Capital Social', 'Pasivo', 2, 'Examen'),
(5, 'Acreedores', 'Pasivo', 2, 'Examen'),
(6, 'Proveedores', 'Pasivo', 4, 'Examen'),
(7, 'Gastos de operación', 'Gasto', 2, 'Examen'),
(8, 'Ventas', 'Venta', 2, 'Examen'),
(9, 'Costo de ventas', 'Costo', 4, 'Examen'),
(10, 'Clientes', 'Activo', 2, 'Examen');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_balanza`
--

CREATE TABLE `examen_balanza` (
  `ID` int(11) NOT NULL,
  `Total_deudor` varchar(50) DEFAULT NULL,
  `Total_acreedor` varchar(50) DEFAULT NULL,
  `Total_saldo_deudor` varchar(50) DEFAULT NULL,
  `Total_saldo_acreedor` varchar(50) DEFAULT NULL,
  `NombreEsquema` varchar(50) DEFAULT NULL,
  `Examen_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examen_balanza`
--

INSERT INTO `examen_balanza` (`ID`, `Total_deudor`, `Total_acreedor`, `Total_saldo_deudor`, `Total_saldo_acreedor`, `NombreEsquema`, `Examen_ID`) VALUES
(1, '106000', '12900', '93100', '0', 'Bancos', 1),
(2, '12000', '0', '12000', '0', 'Equipo de Oficina', 1),
(3, '23000', '15000', '8000', '0', 'Almacen', 1),
(4, '0', '90000', '0', '90000', 'Capital Social', 1),
(5, '4000', '12000', '0', '8000', 'Acreedores', 1),
(6, '8000', '23000', '0', '15000', 'Proveedores', 1),
(7, '900', '0', '900', '0', 'Gastos de operación', 1),
(8, '0', '25000', '0', '25000', 'Ventas', 1),
(9, '15000', '0', '15000', '0', 'Costo de ventas', 1),
(10, '15000', '6000', '9000', '0', 'Clientes', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_montos`
--

CREATE TABLE `examen_montos` (
  `ID` int(11) NOT NULL,
  `Monto_deudor` varchar(50) DEFAULT NULL,
  `Monto_acreedor` varchar(50) DEFAULT NULL,
  `NombreEsquema` varchar(50) DEFAULT NULL,
  `Examen_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examen_montos`
--

INSERT INTO `examen_montos` (`ID`, `Monto_deudor`, `Monto_acreedor`, `NombreEsquema`, `Examen_ID`) VALUES
(1, '90000', '4000', 'Bancos', 1),
(2, '6000', '8000', 'Bancos', 1),
(3, '10000', '900', 'Bancos', 1),
(4, '12000', '0', 'Equipo de Oficina', 1),
(5, '16000', '8000', 'Almacen', 1),
(6, '7000', '7000', 'Almacen', 1),
(7, '0', '90000', 'Capital Social', 1),
(8, '4000', '12000', 'Acreedores', 1),
(9, '8000', '16000', 'Proveedores', 1),
(10, '0', '7000', 'Proveedores', 1),
(11, '900', '0', 'Gastos de operación', 1),
(12, '0', '25000', 'Ventas', 1),
(13, '8000', '0', 'Costo de ventas', 1),
(14, '7000', '0', 'Costo de ventas', 1),
(15, '15000', '6000', 'Clientes', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba`
--

CREATE TABLE `prueba` (
  `ID` int(11) NOT NULL,
  `NombreEsquema` varchar(50) DEFAULT NULL,
  `TipoCuenta` varchar(50) DEFAULT NULL,
  `Montos` int(11) DEFAULT NULL,
  `Cuenta` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prueba`
--

INSERT INTO `prueba` (`ID`, `NombreEsquema`, `TipoCuenta`, `Montos`, `Cuenta`) VALUES
(1, 'proovedores', 'Pasivo', 2, 'prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_balanza`
--

CREATE TABLE `prueba_balanza` (
  `ID` int(11) NOT NULL,
  `Total_deudor` varchar(50) DEFAULT NULL,
  `Total_acreedor` varchar(50) DEFAULT NULL,
  `Total_saldo_deudor` varchar(50) DEFAULT NULL,
  `Total_saldo_acreedor` varchar(50) DEFAULT NULL,
  `NombreEsquema` varchar(50) DEFAULT NULL,
  `prueba_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_montos`
--

CREATE TABLE `prueba_montos` (
  `ID` int(11) NOT NULL,
  `Monto_deudor` varchar(50) DEFAULT NULL,
  `Monto_acreedor` varchar(50) DEFAULT NULL,
  `NombreEsquema` varchar(50) DEFAULT NULL,
  `prueba_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `examen`
--
ALTER TABLE `examen`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `examen_balanza`
--
ALTER TABLE `examen_balanza`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Examen_ID` (`Examen_ID`);

--
-- Indices de la tabla `examen_montos`
--
ALTER TABLE `examen_montos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Examen_ID` (`Examen_ID`);

--
-- Indices de la tabla `prueba`
--
ALTER TABLE `prueba`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `prueba_balanza`
--
ALTER TABLE `prueba_balanza`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `prueba_ID` (`prueba_ID`);

--
-- Indices de la tabla `prueba_montos`
--
ALTER TABLE `prueba_montos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `prueba_ID` (`prueba_ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `examen`
--
ALTER TABLE `examen`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `examen_balanza`
--
ALTER TABLE `examen_balanza`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `examen_montos`
--
ALTER TABLE `examen_montos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `prueba`
--
ALTER TABLE `prueba`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `prueba_balanza`
--
ALTER TABLE `prueba_balanza`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prueba_montos`
--
ALTER TABLE `prueba_montos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `examen_balanza`
--
ALTER TABLE `examen_balanza`
  ADD CONSTRAINT `examen_balanza_ibfk_1` FOREIGN KEY (`Examen_ID`) REFERENCES `examen` (`ID`);

--
-- Filtros para la tabla `examen_montos`
--
ALTER TABLE `examen_montos`
  ADD CONSTRAINT `examen_montos_ibfk_1` FOREIGN KEY (`Examen_ID`) REFERENCES `examen` (`ID`);

--
-- Filtros para la tabla `prueba_balanza`
--
ALTER TABLE `prueba_balanza`
  ADD CONSTRAINT `prueba_balanza_ibfk_1` FOREIGN KEY (`prueba_ID`) REFERENCES `prueba` (`ID`);

--
-- Filtros para la tabla `prueba_montos`
--
ALTER TABLE `prueba_montos`
  ADD CONSTRAINT `prueba_montos_ibfk_1` FOREIGN KEY (`prueba_ID`) REFERENCES `prueba` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

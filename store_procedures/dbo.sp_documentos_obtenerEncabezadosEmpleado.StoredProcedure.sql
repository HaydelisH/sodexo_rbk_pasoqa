USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerEncabezadosEmpleado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 11-04-2019
-- Descripcion: Obtiene los datos variables de un Empleado de un Contrato 
-- Ejemplo:exec [sp_documentos_obtenerVariablesEmpleado] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerEncabezadosEmpleado]

AS
BEGIN	
	SET NOCOUNT ON;			
   

	SELECT	TOP 1
		CONVERT(CHAR(10), P.fechanacimiento,105) As FechaNacimiento,
		E.rolid As Rol,
		E.rolid As RolEmpleado,
		E.idEstadoEmpleado AS EstadoEmpleado,
		ec.idEstadoCivil As idEstadoCivil,
		ec.Descripcion As EstadoCivil,
		P.apmaterno As ApellidoMaternoTrabajador,
		P.appaterno As ApellidoPaternoTrabajador,
		P.ciudad As CiudadTrabajador,
		P.ciudad,
		P.ciudad as Ciudad,
		P.comuna As Comuna,
		P.comuna,
		P.correo As CorreoElectronicoEmpleado,
		P.correo As Correo,
		isnull(P.direccion,'') + ' ' + isnull(P.comuna,'') + ' ' + isnull(P.ciudad,'') As Direccion,
		P.direccion,
		P.nacionalidad As Nacionalidad,
		isnull(P.nombre,'') + ' ' + isnull(P.appaterno,'') + ' ' + isnull(P.apmaterno,'') As Nombre,
		P.nombre As NombreTrabajador,
		P.personaid As Rut,
		P.personaid As RutTrabajador,
		R.Descripcion As DescripcionRol,
		R.rolid
	FROM personas P				 
		LEFT JOIN Empleados E				  ON p.personaid = E.empleadoid
		LEFT JOIN Roles R					  ON E.rolid = R.rolid
		LEFT  JOIN EstadoCivil ec			  ON p.estadocivil = ec.idEstadoCivil
END
GO

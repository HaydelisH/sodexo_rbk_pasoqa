USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerVariablesEmpleado_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- Modificado: gdiaz 11/04/2021
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerVariablesEmpleado_20210920]
	@idDocumento INT
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
		IF EXISTS (SELECT idDocumento FROM Contratos WHERE (idDocumento = @idDocumento AND Eliminado = 0 ) )
			BEGIN
				SELECT		
					CONVERT(CHAR(10), P.fechanacimiento,105) As FechaNacimiento,
					E.rolid As Rol,
					E.rolid As RolEmpleado,
					E.idEstadoEmpleado AS EstadoEmpleado,
					ec.Descripcion As EstadoCivil,
					P.apmaterno As ApMatTrabajador,
					P.appaterno As ApPatTrabajador,
					P.apmaterno As ApellidoMaterno,
					P.appaterno As ApellidoPaterno,
					P.ciudad As CiudadTrabajador,
					P.ciudad,
					P.ciudad As Ciudad,
					P.comuna As Comuna,
					P.comuna,
					P.correo As CorreoElectronicoEmpleado,
					P.correo As Correo,
					P.direccion + ' ' + P.comuna + ' ' + P.ciudad  As DireccionCompleta,
					P.direccion As Direccion,
					P.direccion,
					P.nacionalidad As Nacionalidad,
					P.nombre As Nombre,
					P.nombre + ' ' + P.appaterno + ' ' + p.apmaterno As NombreTrabajador,
					P.personaid As Rut,
					P.personaid As RutTrabajador,
					R.Descripcion As DescripcionRol
				FROM [Contratos] C
					INNER JOIN ContratoDatosVariables CDT ON CDT.idDocumento = C.idDocumento
					INNER JOIN personas P				  ON p.personaid = CDT.Rut
					LEFT JOIN Empleados E				  ON CDT.Rut = E.empleadoid
					LEFT JOIN Roles R					  ON E.rolid = R.rolid
					LEFT JOIN EstadoCivil ec			  ON p.estadocivil = ec.idEstadoCivil
				WHERE 
					C.idDocumento = @idDocumento
			END 
	END
END
GO

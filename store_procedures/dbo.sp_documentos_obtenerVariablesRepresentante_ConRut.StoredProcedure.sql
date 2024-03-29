USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerVariablesRepresentante_ConRut]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 11-04-2019
-- Descripcion: Obtiene los datos variables de un Contrato 
-- Ejemplo:exec [sp_documentos_obtenerVariablesRepresentante] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerVariablesRepresentante_ConRut]
	@idDocumento INT,
	@Firmante VARCHAR(10)
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
		IF EXISTS (SELECT idDocumento FROM Contratos WHERE (idDocumento = @idDocumento AND Eliminado = 0 ) )
			BEGIN
				SELECT		
					P.personaid As Rut,
					P.nombre + ' ' + P.appaterno + ' ' + isnull(P.apmaterno,'') As Nombre,
					P.direccion,
					P.direccion As Direccion,
					P.comuna,
					P.ciudad,
					isnull(P.Direccion,'') + ' ' + isnull(P.Comuna,'') + ' ' + isnull(P.Ciudad,'') As DireccionCompleta,
					P.correo,
					P.nacionalidad,
					CONVERT(CHAR(10), P.fechanacimiento,105) As FechaNacimiento,
					isnull(ec.Descripcion,'')  as estadocivil,
					f.cargo as Cargo
				FROM [Contratos] C
					INNER JOIN ContratoFirmantes CF ON C.idDocumento = CF.idDocumento AND C.RutEmpresa  = CF.RutEmpresa AND CF.RutFirmante = @Firmante
					INNER JOIN Personas P ON CF.RutFirmante = P.personaid
					LEFT JOIN EstadoCivil ec ON P.estadocivil = ec.idEstadoCivil
					INNER JOIN Firmantes f on f.RutUsuario = P.personaid AND F.RutEmpresa = C.RutEmpresa
				WHERE 
					C.idDocumento = @idDocumento 
			END 
	END
END
GO

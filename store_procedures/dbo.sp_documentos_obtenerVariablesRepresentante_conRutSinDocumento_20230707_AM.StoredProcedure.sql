USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerVariablesRepresentante_conRutSinDocumento_20230707_AM]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- Creado el: 11-04-2019
-- Descripcion: Obtiene los datos variables de un Contrato 
-- Ejemplo:exec [sp_documentos_obtenerVariablesRepresentante_conRutSinDocumento]
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerVariablesRepresentante_conRutSinDocumento_20230707_AM]
	@Firmante VARCHAR(10)
AS
BEGIN	
	SET NOCOUNT ON;			
   
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
			P.nacionalidad AS Nacionalidad,
			CONVERT(CHAR(10), P.fechanacimiento,105) As FechaNacimiento,
			isnull(ec.Descripcion,'')  as estadocivil,
			f.cargo as Cargo
		FROM ContratoFirmantes CF 
			INNER JOIN Personas P ON CF.RutFirmante = P.personaid
			LEFT JOIN EstadoCivil ec ON P.estadocivil = ec.idEstadoCivil
			INNER JOIN Firmantes f on f.RutUsuario = P.personaid
		WHERE 
			CF.RutFirmante = @Firmante
	END
END
GO

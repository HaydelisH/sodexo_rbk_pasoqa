USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerEncabezadosRepresentante]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 11-04-2019
-- Descripcion: Obtiene los datos variables de un representante de un Contrato 
-- Ejemplo:exec [sp_documentos_obtenerVariablesRepresentante] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerEncabezadosRepresentante]
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
		
		SELECT TOP 1	
		
			UPPER(P.personaid) As Rut,
			isnull(P.nombre,'') + ' ' + isnull(P.appaterno,'') + ' ' + isnull(P.apmaterno,'') As Nombre,
			P.direccion,
			P.direccion As Direccion,
			P.comuna,
			P.comuna As Comuna,
			P.ciudad,
			P.ciudad As Ciudad,
			isnull(P.Direccion,'') + ' ' + isnull(P.Comuna,'') + ' ' + isnull(P.Ciudad,'') As DireccionCompleta,
			P.correo,
			P.correo As Correo,
			P.nacionalidad,
			P.nacionalidad As Nacionalidad, 
			CONVERT(CHAR(10), P.fechanacimiento,105) As FechaNacimiento,
			isnull(ec.Descripcion,'')  as estadocivil,
			isnull(ec.Descripcion,'')  as EstadoCivil

		FROM [Contratos] C
			INNER JOIN ContratoFirmantes CF ON C.idDocumento = CF.idDocumento AND C.RutEmpresa  = CF.RutEmpresa
			INNER JOIN Personas P ON CF.RutFirmante = P.personaid	
			LEFT JOIN EstadoCivil ec ON P.estadocivil = ec.idEstadoCivil
	END
END
GO

USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerEncabezadosEmpresa]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 11-04-2019
-- Descripcion: Obtiene los datos variables de la empresa de un Contrato 
-- Ejemplo:exec [sp_documentos_obtenerVariablesEmpresa] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerEncabezadosEmpresa]

AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
		
		SELECT	TOP 1	
			E.RutEmpresa,
			E.RutEmpresa As Rut,
			E.RazonSocial,
			isnull(E.Direccion,'') + ' ' + isnull(E.Comuna,'') + ' ' + isnull(E.Ciudad,'') As DireccionCompleta,
			isnull(E.Direccion,'') + ' ' + isnull(E.Comuna,'') + ' ' + isnull(E.Ciudad,'') As Domicilio,
			E.Ciudad,
			E.Comuna,
			E.Direccion

		FROM [Contratos] C
			
			INNER JOIN Empresas	E			ON E.RutEmpresa = C.RutEmpresa
	END
END
GO

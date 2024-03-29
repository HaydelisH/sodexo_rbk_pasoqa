USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerVariablesEmpresaConRutEmpresa]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 11-04-2019
-- Descripcion: Obtiene los datos variables de un Contrato 
-- Ejemplo:exec [sp_documentos_obtenerVariablesEmpresa] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerVariablesEmpresaConRutEmpresa]
	@RutEmpresa VARCHAR(10)
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
		
		SELECT		
			E.RutEmpresa,
			E.RutEmpresa As Rut,
			E.RazonSocial,
			isnull(E.Direccion,'') + ' ' + isnull(E.Comuna,'') + ' ' + isnull(E.Ciudad,'') As DireccionCompleta,
			isnull(E.Direccion,'') + ' ' + isnull(E.Comuna,'') + ' ' + isnull(E.Ciudad,'') As Domicilio,
			E.Ciudad,
			E.Comuna,
			E.Direccion
		FROM Empresas	E	
		WHERE 
			E.RutEmpresa = @RutEmpresa
	END
END
GO

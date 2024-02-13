USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Listado Empresa
-- Ejemplo:exec sp_empresas_listado 
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_listado]
AS
BEGIN
	
       --Variables
	DECLARE @RutEmpresa VARCHAR(10)
	DECLARE @RazonSocial VARCHAR(50);
	
	SELECT RutEmpresa,RazonSocial
	FROM Empresas
	WHERE Eliminado=0
	RETURN                                                    
END
GO

USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_listadoCombo]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Listado Empresa
-- Ejemplo:exec sp_rl_proveedores_listadoCombo 
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_listadoCombo]
AS
BEGIN
	
	SELECT 
        RutProveedor,
        NombreProveedor
	FROM rl_proveedores
	WHERE Eliminado=0
	Order By NombreProveedor 
	RETURN                                                    
END
GO

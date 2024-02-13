USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_obtener_rutproveedor]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor:CSB
-- Creado el: 13/06/2018
-- Descripcion: Obtener Cliente
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_proveedores_obtener_rutproveedor '9798215-5'
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_obtener_rutproveedor]
	@RutProveedor VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	
	SELECT RutProveedor, NombreProveedor, Direccion, Comuna, Ciudad FROM rl_Proveedores 
	WHERE RutProveedor = @RutProveedor AND Eliminado=0

                                    
END
GO

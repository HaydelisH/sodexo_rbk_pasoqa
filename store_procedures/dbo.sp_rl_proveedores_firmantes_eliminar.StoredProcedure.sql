USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_firmantes_eliminar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Eliminar un repesentante
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_proveedores_representantes_eliminar 'eliminar','22604213-K','18629109-3' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_firmantes_eliminar]
	@pAccion CHAR(60),
	@RutProveedor VARCHAR (10),
	@RutUsuario VARCHAR (10),
	@RutEmpresa  VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='eliminar') 
    BEGIN
		IF EXISTS (SELECT RutUsuario FROM rl_Firmantes_Proveedores WHERE RutUsuario = @RutUsuario  AND RutProveedor = @RutProveedor)
			BEGIN
			 DELETE FROM rl_Firmantes_Proveedores WHERE RutUsuario = @RutUsuario AND RutProveedor = @RutProveedor 
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTE REPRESENTANTE FUE ELIMINADO'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO

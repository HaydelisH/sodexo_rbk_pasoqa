USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_lugarespago_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO


-- =============================================
-- Autor: Cristian Soto
-- Creado el: 30/06/2011
-- Descripcion:	Obtiene lugar de pago
-- Ejemplo:exec sp_lugarespago_eliminar 'rrr'
-- =============================================
CREATE PROCEDURE [dbo].[sp_lugarespago_eliminar]
	@pempresaid NVARCHAR(14),
	@plugarpagoid NVARCHAR (14) --id empresA
AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT
	
	IF EXISTS ( SELECT  C.idDocumento FROM ContratoDatosVariables CDV INNER JOIN Contratos C ON CDV.idDocumento = C.idDocumento WHERE CDV.lugarpagoid = @plugarpagoid AND C.Eliminado = 0 AND C. RutEmpresa = @pempresaid)
		BEGIN 
			SELECT @lmensaje = 'No se puede eliminar Lugar de Pago, porque posee documentos asociados '
			SELECT @error = 1
			SELECT @lmensaje as mensaje, @error as error
			RETURN;
		END
	
	IF EXISTS ( SELECT centrocostoid FROM centroscosto WHERE lugarpagoid = @plugarpagoid ) 
		BEGIN 
			SELECT @lmensaje = 'No se puede eliminar Lugar de Pago, porque posee Centros de Costo asociados'
			SELECT @error = 1
			SELECT @lmensaje as mensaje, @error as error
			RETURN;
		END 
		
	IF NOT EXISTS( SELECT lugarpagoid FROM lugarespago WHERE lugarpagoid = @plugarpagoid )  
		BEGIN 
			SELECT @lmensaje = 'Lugar de Pago seleccionado no existe'
			SELECT @error = 1
		END
	ELSE
		BEGIN
			IF NOT EXISTS( SELECT lugarpagoid FROM lugarespago WHERE lugarpagoid = @plugarpagoid and empresaid = @pempresaid )  
			BEGIN 
				SELECT @lmensaje = 'Lugar de Pago seleccionado no exite para esa empresa'
				SELECT @error = 1
			END
			ELSE
				BEGIN
					BEGIN TRY	
						DELETE FROM lugarespago WHERE lugarpagoid = @plugarpagoid and empresaid = @pempresaid 
					END TRY  
					BEGIN CATCH  
						SET @error		= ERROR_NUMBER()
						SET @lmensaje	= ERROR_MESSAGE()
					END CATCH 
				END
		END
			
	SELECT @lmensaje as mensaje, @error as error
	RETURN;
END
GO

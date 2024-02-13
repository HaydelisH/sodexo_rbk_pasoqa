USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_eliminar]    Script Date: 1/22/2024 7:21:13 PM ******/
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
CREATE PROCEDURE [dbo].[sp_centroscosto_eliminar]
	@pempresaid NVARCHAR(14),
	@plugarpagoid NVARCHAR (14), 
	@centrocostoid			NVARCHAR(14)
	
AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT
	
	IF EXISTS ( SELECT  C.idDocumento FROM ContratoDatosVariables CDV INNER JOIN Contratos C ON CDV.idDocumento = C.idDocumento WHERE CDV.lugarpagoid = @plugarpagoid AND CDV.CentroCosto = @centrocostoid AND C.RutEmpresa = @pempresaid AND C.Eliminado = 0 )
		BEGIN 
			SELECT @lmensaje = 'No se puede eliminar el Centro de Costo, porque posee documentos asociados '
			SELECT @error = 1
			SELECT @lmensaje as mensaje, @error as error
			RETURN;
		END
		
	IF NOT EXISTS( SELECT centrocostoid FROM centroscosto WHERE centrocostoid = @centrocostoid AND lugarpagoid = @plugarpagoid AND empresaid = @pempresaid)  
		BEGIN 
			SELECT @lmensaje = 'La Centro de Costo seleccionada no existe'
			SELECT @error = 1
		END
	ELSE
		BEGIN
			BEGIN TRY	
				DELETE FROM centroscosto WHERE centrocostoid = @centrocostoid  AND lugarpagoid = @plugarpagoid AND empresaid = @pempresaid
			END TRY  
			BEGIN CATCH  
				SET @error		= ERROR_NUMBER()
				SET @lmensaje	= ERROR_MESSAGE()
			END CATCH 
		END
			
	SELECT @lmensaje as mensaje, @error as error
	RETURN;
END

GO

USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_lugarespago_modificar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER OFF
GO

-- =============================================
-- Autor: Cristian Soto
-- Creado el: 30/06/2011
-- Descripcion:	Obtiene lugar de pago
-- Ejemplo:exec sp_lugarespago_modificar 'ejemplo','nuevo','dos'
-- =============================================
CREATE PROCEDURE [dbo].[sp_lugarespago_modificar]
	@plugarpagoid		NVARCHAR (14), --id lugar pago
	@pnombrelugarpago	NVARCHAR(50), 
	@pempresaid				NVARCHAR(10)
AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @lmensaje VARCHAR(200)
	DECLARE @error INT
	DECLARE @basex			VARCHAR(20)
	DECLARE @base_gestor		VARCHAR(20)	
	DECLARE @actualizarGestor		VARCHAR(20)
	DECLARE @basecliente	VARCHAR(20)
	DECLARE @sqlString nvarchar(max)
	DECLARE @Param nvarchar(max)
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @totalreg DECIMAL (9,2);
	
	--para deducir base Gestor
	--SELECT @basex =  DB_NAME() 
	--SELECT TOP 1 @basecliente = splitdata FROM dbo.Split (@basex,'_')
	--SET @base_gestor = @basecliente + '_' + 'Gestor'
	--fin
	
	SELECT @base_gestor = parametro FROM Parametros WHERE idparametro = 'gestor'
	SELECT @actualizarGestor = parametro FROM Parametros WHERE idparametro  = 'actualizarGestorEstructura'
	
	BEGIN TRY  
				BEGIN TRANSACTION 
				
	IF EXISTS(  SELECT lugarpagoid FROM lugarespago WHERE lugarpagoid = @plugarpagoid AND empresaid = @pempresaid )
		BEGIN 
			
			UPDATE lugarespago SET 
				nombrelugarpago = @pnombrelugarpago, 
				empresaid = @pempresaid
			WHERE 
				lugarpagoid = @plugarpagoid AND empresaid = @pempresaid
				
			SELECT @lmensaje = ''
			SELECT @error = 0
		END
	ELSE
		BEGIN
			SELECT @lmensaje = 'Lugar de Pago no existe'
			SELECT @error = 1
		END
	IF (@actualizarGestor = 1)
		BEGIN	
			--Agregar registro de Gestor		
			SET @totalreg = 0
			SET @sqlString = N'SELECT @totalreg = COUNT(*) from ' + @base_gestor + '.dbo.lugarespago where lugarpagoid = ' + '''' + @plugarpagoid + '''' + ' AND empresaid= ' + '''' + @pempresaid + '''' 
			SET @Param = N'@totalreg DECIMAL (9,2) OUTPUT'
			EXECUTE sp_executesql @sqlString,  @Param, @totalreg = @totalreg OUTPUT;
			
			IF ( @totalreg = 0 ) 
				BEGIN 
				
					SET @sqlString = N'INSERT INTO ' + @base_gestor + '.dbo.lugarespago (lugarpagoid, nombrelugarpago, empresaid) 
					VALUES(' +  '''' + @plugarpagoid + '''' + ',' + '''' + @pnombrelugarpago + '''' + ',' + '''' + @pempresaid + '''' + ')'
					EXECUTE sp_executesql @sqlString;
				END
		END; 
			
			COMMIT TRANSACTION;
	END TRY  
		BEGIN CATCH  
			SELECT @lmensaje = 'Hubo un error en la actualizacion de la estructura, intente nuevamente. Si el problema persiste comuniquese con soporte.'
			SELECT @error = 2
			ROLLBACK TRANSACTION ;
		END CATCH ;
	SELECT @lmensaje as mensaje, @error as error
	
	RETURN;
END
GO

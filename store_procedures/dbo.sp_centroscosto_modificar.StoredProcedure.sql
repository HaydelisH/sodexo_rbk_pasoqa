USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_modificar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

--sp_centroscosto_modificar '1','Docentes','CESR','65145586-3','','',''
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 15/04/2019
-- Descripcion: Agregar a un centro de costo 
-- Ejemplo:exec sp_centrocosto_modificar
-- =============================================
CREATE PROCEDURE [dbo].[sp_centroscosto_modificar]
	@centrocostoid			NVARCHAR(14),
	@nombrecentrocosto		NVARCHAR(80),
	@lugarpagoid			NVARCHAR(14),
	@empresaid				NVARCHAR(10),
	@direccion				NVARCHAR(60),
	@comuna					NVARCHAR(40),
	@ciudad					NVARCHAR(40)
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
	--Si existe para el mismo lugar de pago y cc, solo modifico el nombre
	IF EXISTS ( SELECT centrocostoid FROM centroscosto WHERE centrocostoid = @centrocostoid AND lugarpagoid = @lugarpagoid AND empresaid = @empresaid) 
		BEGIN
			--Insertar en la tabla Personas 
			UPDATE centroscosto SET 
				nombrecentrocosto = @nombrecentrocosto,
				lugarpagoid = @lugarpagoid,
				empresaid = @empresaid,
				direccion = @direccion,
				comuna = @comuna,
				ciudad = @ciudad
			WHERE 
				centrocostoid = @centrocostoid AND lugarpagoid = @lugarpagoid AND empresaid = @empresaid
		
			SET @lmensaje = ''
			SET @error = 0
		END
	ELSE
		BEGIN 
			SET @lmensaje = 'El Centro de Costo ya existe para Lugar de Pago seleccionado/a '
			SET @error = 1
		END
		
	IF (@actualizarGestor = 1)
		BEGIN	
			--Agregar registro de Gestor		
			SET @totalreg = 0
			SET @sqlString = N'SELECT @totalreg = COUNT(*) from ' + @base_gestor + '.dbo.centroscosto where centrocostoid = ' + '''' + @centrocostoid + '''' + 'AND lugarpagoid = ' + '''' + @lugarpagoid + '''' + 'AND empresaid = ' + ''''+ @empresaid+ ''''
			SET @Param = N'@totalreg DECIMAL (9,2) OUTPUT'
			EXECUTE sp_executesql @sqlString,  @Param, @totalreg = @totalreg OUTPUT;
		
			IF ( @totalreg = 0 ) 
				BEGIN 
					SET @sqlString = N'INSERT INTO ' + @base_gestor + '.dbo.centroscosto (centrocostoid, nombrecentrocosto, idCliente) 
				VALUES(' +  '''' + @centrocostoid + '''' + ',' + '''' + @nombrecentrocosto + '''' + ',' + '''' + @lugarpagoid + '''' + ')'
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
		
	SELECT @lmensaje as mensaje , @error as error
END
GO

USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_feriados_modificar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Modifica los registros 
-- Ejemplo:exec sp_feriados_modificar 'modificar',1,'ejemplo 2'
-- =============================================
CREATE PROCEDURE [dbo].[sp_feriados_modificar]
	@pAccion CHAR(60),
	@idFeriado INT,
	@Descripcion VARCHAR (50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='modificar') 
    BEGIN
      IF  NOT EXISTS (SELECT idFeriado FROM Feriados WHERE idFeriado = @idFeriado)
        BEGIN
			SELECT @lmensaje = 'ESTA FECHA NO EXISTE'
			SELECT @error = 1
			SELECT @error AS error, @lmensaje AS mensaje 
			RETURN
        END
        
	  IF EXISTS (SELECT idFeriado FROM Feriados WHERE idFeriado = @idFeriado)
	    BEGIN
			UPDATE Feriados SET Descripcion = @Descripcion
			WHERE idFeriado = @idFeriado
		    SELECT @lmensaje = ''
			SELECT @error = 0
			SELECT @error AS error, @lmensaje AS mensaje 
			RETURN
	    END
	END	
	
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO

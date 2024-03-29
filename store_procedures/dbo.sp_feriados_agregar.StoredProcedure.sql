USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_feriados_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Agrega feriado
-- Ejemplo:exec sp_feriados_agregar 'agregar','2018-06-29','prueba descripcion'
-- =============================================
CREATE PROCEDURE [dbo].[sp_feriados_agregar]
	@pAccion CHAR(60),
	@Fecha DATE,
	@Descripcion VARCHAR(50) 
	 
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	DECLARE @eliminado  BIT;
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		IF NOT EXISTS (SELECT idFeriado FROM Feriados WHERE Fecha = @Fecha )
			BEGIN 	
				INSERT INTO Feriados(Fecha,Descripcion) VALUES 
				(@Fecha,@Descripcion) 
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTA FECHA YA ESTA EN USO'
				SELECT @error = 1
			END 
	END
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO

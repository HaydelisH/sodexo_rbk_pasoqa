USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_descargas_agregar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/11/2018
-- Descripcion:  Agregar un Archivo para descargar
-- Ejemplo:exec sp_descargas_agregar 'Nombre','Tipo','Ruta'
-- =============================================
CREATE PROCEDURE [dbo].[sp_descargas_agregar]
	@Nombre VARCHAR (50), 
	@Tipo   VARCHAR (50),
	@Ruta   VARCHAR (MAX),
	@B64 VARCHAR(MAX),
	@Descripcion VARCHAR(50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	
	DECLARE @Archivo VARBINARY(MAX);
	
	SELECT @Archivo= CONVERT(varbinary(max), @B64)
			
    -- Insert statements for procedure here
	INSERT INTO Descargas (Nombre, Tipo, Ruta, Fecha, B64, Descripcion) VALUES(@Nombre,@Tipo,@Ruta, GETDATE(), @Archivo, @Descripcion)
   
END
GO
